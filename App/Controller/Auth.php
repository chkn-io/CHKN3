<?php

/**
* 
*/
class Auth extends Controller
{
	public $CRUD;
	public $helper;
    public $session;

	function __construct(){
        if(QUERY_BUILDER == 1){
            $this->CRUD = new CRUD;
        }
		$this->helper = new global_helper;
        if(SESSION == 1){
            $this->session = new App_Session;
        }
	}

	function check($data,$redirect_url){
        $keys = array_keys($data);
    	if($this->session_check("auth")){
    		if($this->session_get("auth")[$keys[0]] != $data[$keys[0]]){
				$this->locate($redirect_url);
			}
    	}else{
    		$this->locate($redirect_url);
    	}
    }

    function user($data){
        $auth = array();
        $array_val = null;
        if($this->session->check("auth")){
            foreach ($data as $value) {
             $auth[$value] = $this->session->get("auth")[$value];
            }
        }
        return $auth;
    }

    function login($table,$data){
		$keys = (array_keys($data));
        echo $data[$keys[0]];
		$users = $this->CRUD->select($table)
    		->where($keys[0],'=',$data[$keys[0]])
    		->fetch();

    	if(count($data) != 0){
    		$decrypt_password = $this->helper->decrypt($users[0][$keys[1]]);
    		if($decrypt_password == $data[$keys[1]]){
    			$this->session_put("auth",$users[0]);
                $this->session_put("auth_message",["message" => "Authentication Success","status"=>"success"]);
    			if(isset($data["url"])){
    				if(isset($data["url"]["success"])){
                        $this->locate($data["url"]["success"]);
                    }else{
                        return array("message"=>"Success","status"=>"success");
                    }
    			}else{
                    return array("message"=>"Success","status"=>"success");
    			}

    		}else{
                $this->session_put("auth_message",["message" => "Wrong Username or Password","status"=>"error"]);
    			if(isset($data["url"])){
                    if(isset($data["url"]["failed"])){
                        $this->locate($data["url"]["failed"]);
                    }else{
                        return array("message"=>"Incorrect Password","status"=>"error");
                    }
    			}else{
                    return array("message"=>"Incorrect Password","status"=>"error");
    			}
    		}
    	}else{
            if(isset($data["url"])){
                $this->session_put("auth_message",["message" => "Error","status"=>"error"]);
                if(isset($data["url"]["failed"])){
                    $this->locate($data["url"]["failed"]);
                }else{
                    return array("message"=>"Incorrect Username","status"=>"error");
                }
            }else{
                return array("message"=>"Incorrect Username","status"=>"error");
            }
            
        }

    }

    function register($table,$data){
        if (isset($data["password"])) {
            $data["password"] = $this->helper->encrypt($data["password"]);
        }

        try {
            $query = "INSERT INTO ".$table;

            $columns = "(";

            $values = "(";

            foreach ($data as $key => $value) {
                if(is_array($value)){
                    break;
                }
               $columns = $columns . $key .",";

               $values = $values."'".$value."',";
            }

            $columns = rtrim($columns,",");

            $columns = $columns .")";

            $values = rtrim($values,",");

            $values = $values .")";

            $query = $query ." ". $columns."VALUES".$values;

            $users = $this->CRUD->query($query)
                    ->execute();

            $users = $this->CRUD->select($table)
                    ->where('id','=',$this->CRUD->lastInsertId())
                    ->fetch();

            $this->session_put("auth",$users[0]);

            if(isset($data["url"]["success"])){
               $this->session_put("auth_message",["message" => "success"]);
               $this->locate($data["url"]["success"]);
            }else{
                $this->session_put("auth_message",["message" => "success"]);
                return 1;
            }
        } catch (Exception $e) {
            if(isset($data["url"]["failed"])){
               $this->session_put("auth_message",["message" => "SQL Error"]);
               $this->session_put("hasError",1);
               $this->locate($data["url"]["failed"]);
            }else{
                $this->session_put("auth_message",["message" => "SQL Error"]);
                $this->session_put("hasError",1);
                return 0;
            }
        }
        
    }

    function message(){
        if ($this->session->check("auth_message")) {
            return $this->session_get("auth_message")["message"];   
        }
        
    }

    function role(){
        return $this->session_get("auth")["role"];
    }

    function isSuccess(){
         if ($this->session->check("auth_message")) {
            if ($this->session_get("auth_message")["message"] == "success") {
                return true;
            }else{
                return false;
            }
         }else{
            return Null;
         }
    }

    function hasError(){
        return $this->session_get("auth_error");
    }

    function logout($url = ""){
        $this->session->clear("auth");
        $this->session->clear("auth_message");
        if($url == ""){
            return ["message"=>"Logout","status"=>1];
        }else{
            $this->locate($url);
        }
    }
}

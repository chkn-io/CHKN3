<?php
class CHKNConsole extends Controller{

	public function console(){
		$this->console_interface();
	}

	protected function console_interface(){
		$this->title("Hello WOrld");
		$this->con_interface("console");
		$this->assign("console:style","App/Controller/src/css/style.css");
		$this->assign("console:script","App/Controller/src/js/script.js");
		$this->assign("console:jquery","App/Controller/src/js/jquery.js");
		$this->show();
	}

	public function console_push(){
		$this->session->put("console_trigger","");
		echo $this->response(["data"=>$this->session->get("console_collection")],200);
		unset($_SESSION["console_collection"]);
	}

	public function check_trigger(){
		if($this->session->check("console_trigger")){
			if($this->session->get("console_trigger") == 1){
				echo 1;
			}
		}
	}

	public function create_controller($controller = ""){
		$controller = str_replace(" ","",$controller);
		if($controller == ""){
			echo $this->response(["message"=>"Undefined value for CREATE CONTROLLER command","type"=>"error"],200);
		}else{
			if(!file_exists("http/Controllers/".$controller."Controller.php")){
				$cont = fopen("http/Controllers/".$controller."Controller.php", "w");
				$content = '<?php
use App\App\Request;
class '.$controller.'Controller extends Controller{
	public function '.$controller.'(){
		//Call Controller Template
		$this->template("index");

		//Change Page Title
		$this->title("");

		//Page Style
		$this->css([
		]);

		//Page Script
		$this->js([]);

		//Page Content
		$this->body("'.$controller.'/index");

		//App Status
		//$this->chkn_status();

		$this->show();
	}
}

';
				
				fwrite($cont, $content);
				fclose($cont); 


				if (!file_exists('view/page/'.$controller)) {
				    mkdir('view/page/'.$controller, 0777, true);
				    if(!file_exists("view/page/".$controller."/index.cvf")){
				    	$page = fopen("view/page/".$controller."/index.cvf", "w");
				    	$pcontent = '
<div class="clearfix"></div>
	<div class="col-md-12">
		<h1 class="text-center">This is the index page of '.$controller.'Controller.</h1>
	</div>	
<hr>';


						fwrite($page, $pcontent);
						fclose($page); 
				    }
				}

				echo $this->response(["message"=>$controller."Controller.php successfully created","type"=>"success"],200);
			}else{
				echo $this->response(["message"=>$controller."Controller.php is already exist","type"=>"error"],200);
			}
		}
	}

	public function dbconfig($connection=""){
		if($connection == ""){
			echo $this->response(["message"=>"Undefined value for ".$_POST["commands"]." command","type"=>"error"],200);
		}else{
			if($connection == "_blank_"){
				$connection = "";
			}
			$path = "config/database.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			while(!feof($file)){
	            $string = fgets($file);
	            if (stripos($string, $_POST["defaults"]) !== false) {
				    $content = str_replace($string,$_POST["defaults"]."=".$connection."\r\n",$content);
				}
	        }

	        file_put_contents($path, $content);
			echo $this->response(["message"=>$_POST["defaults"]." successfully updated to ".$connection,"type"=>"success"],200);
		}
	}

	public function appconfig($connection=""){
		if($connection == ""){
			echo $this->response(["message"=>"Undefined value for ".$_POST["commands"]." command","type"=>"error"],200);
		}else{
			if($connection == "_blank_"){
				$connection = "";
			}
			$path = "config/app.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			while(!feof($file)){
	            $string = fgets($file);
	            if (stripos($string, $_POST["defaults"]) !== false) {
				    $content = str_replace($string,$_POST["defaults"]."=".$connection."\r\n",$content);
				}
	        }

	        file_put_contents($path, $content);
			echo $this->response(["message"=>$_POST["defaults"]." successfully updated to ".$connection,"type"=>"success"],200);
		}
	}

	public function db(){
		if(QUERY_BUILDER == 1){
			$ex = explode(",",$_POST["config_string"]);
			if(count($ex) == 4){
				$path = "config/database.conf";
				$file = fopen($path,"r");
				$content = file_get_contents($path);
				while(!feof($file)){
		            $string = fgets($file);
		            if (stripos($string, "DB_HOST") !== false) {
					    $content = str_replace($string,"DB_HOST=".$ex[0]."\r\n",$content);
					}
					if (stripos($string, "DB_NAME") !== false) {
					    $content = str_replace($string,"DB_NAME=".$ex[1]."\r\n",$content);
					}
					if (stripos($string, "DB_USER") !== false) {
					    $content = str_replace($string,"DB_USER=".$ex[2]."\r\n",$content);
					}
					if (stripos($string, "DB_PASSWORD") !== false) {
					    $content = str_replace($string,"DB_PASSWORD=".$ex[3]."\r\n",$content);
					}
		        }

		        file_put_contents($path, $content);
				echo $this->response(["message"=>"Database Configuration is successfully updated","type"=>"success"],200);
			}else{
				echo $this->response(["message"=>"Incorrect entry for DB command","type"=>"error"],200);
			}
		}else{
			echo $this->response(["message"=>"QUERY_BUILDER is disabled","type"=>"error"],200);
		}
		
	}

	public function defineglobal(){
		$globals = $_POST["globals"];
		$values = $_POST["values"];

		if(str_replace(" ", "", $globals) != ""){
			$path = "config/user.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			$ind = 0;
			while(!feof($file)){
				$string = fgets($file);
				if(stripos($string, $globals) !== false){
					$content = str_replace($string,$globals."=".$values."\r\n",$content);

					$ind++;
				}

			}
			if($ind == 0){
				$new = $globals."=".$values;
				$new_content = $content."\r\n".$new;
			    file_put_contents($path, $new_content);

				echo $this->response(["message"=>$globals." is successfully defined.","type"=>"success"],200);
			}else{

				file_put_contents($path, $content);
				echo $this->response(["message"=>"CONSTANT ".$globals." is successfully updated!","type"=>"success"],200);
			}
			
		}else{
			echo $this->response(["message"=>"Invalid parameter value sent to defineglobal command!","type"=>"error"],200);
		}
		// if(count($ex) == 4){
		// 	echo $this->response(["message"=>"Database Configuration is successfully updated","type"=>"success"],200);
		// }else{
		// 	echo $this->response(["message"=>"Incorrect entry for DB command","type"=>"error"],200);
		// }
	}

	public function installKey(){
		$encryption_key_256bit = base64_encode(openssl_random_pseudo_bytes(32));
		$path = "config/app.conf";
			$file = fopen($path,"r");
			$content = file_get_contents($path);
			while(!feof($file)){
	            $string = fgets($file);
	            if (stripos($string, "APPLICATION_KEY") !== false) {
				    $content = str_replace($string,"APPLICATION_KEY=".$encryption_key_256bit."\r\n",$content);
				}
	        }
	        // echo $encryption_key_256bit;
	        file_put_contents($path, $content);
			echo $this->response(["message"=>"Application Key is successfully installed","type"=>"success"],200);
	}

	public function checkdatabase(){
		$db = $this->CRUD->check_db();
		if(count($db) == 0){
			echo $this->response(["message"=>"Database found","type"=>"success"],200);
		}else{
			echo $this->response(["message"=>"Database not found","type"=>"error"],200);
		}
	}

	public function createtable(){
		if(QUERY_BUILDER == 1){
			$query = 'CREATE table chkn_auth(
		     id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
		     username VARCHAR( 100 ) NOT NULL, 
		     password VARCHAR( 250 ) NOT NULL,
		     role VARCHAR( 50 ) NOT NULL,
		     date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);';
	     	$this->CRUD->query($query)->execute();

	     	$this->CRUD->insert("chkn_auth")
	     				->field("username","admin")
	     				->field("password",$this->helper->encrypt("admin"))
	     				->field("role","admin")
	     				->execute();
	     	echo $this->response(["message"=>"Database found","type"=>"success"],200);
		}else{
			echo $this->response(["message"=>"QUERY_BUILDER is disabled","type"=>"error"],200);
		}
		
	}
	
	public function createauthcontroller(){
		if(!file_exists("http/Controllers/AuthController.php")){
			$cont = fopen("http/Controllers/AuthController.php", "w");
			$content = '<?php
use App\App\Request;
class AuthController extends Controller{
	public function auth(){
		//Call Controller Template
		$this->template("auth");

		//Change Page Title
		$this->title("");

		//Page Style
		$this->css([
		]);

		//Page Script
		$this->js([]);

		//Page Content
		$this->body("auth/index");
		$this->variable("auth_message","");
		if($this->session->check("auth_message")){
			$this->variable("auth_message",$this->session->get("auth_message")["status"]);
		}

		//App Status
		//$this->chkn_status();

		$this->show();
		$this->session->clear("auth_message");
	}

	public function login(Request $r){
		if($r->post("username") != ""){
			$this->auth->login("chkn_auth",[
				"username"=>$r->post("username"),
				"password"=>$r->post("password"),
				"url"=>[
					//Redirect when true
					"success"=>"",
					//Redirect when false
					"failed"=>"auth"
				]
			]);
		}else{
			$this->locate("auth");
			$this->session->put("auth_status","error");
		}
	}

	public function logout(){
		$this->auth->logout("auth");
	}
}



';
				
				fwrite($cont, $content);
				fclose($cont); 


				if (!file_exists('view/page/auth')) {
				    mkdir('view/page/auth', 0777, true);
				    if(!file_exists("view/page/auth/index.cvf")){
				    	$page = fopen("view/page/auth/index.cvf", "w");
				    	$pcontent = '
<div class="col-md-5 login-container">
	<div class="wrapper"> 
		#if("$auth_message" == "error"){
			{{
				<p class="alert alert-danger text-center">Incorrect Username or Password</p>
			}}
		}elseif("$auth_message" == "success"){
			{{
				<p class="alert alert-danger text-center">Authentication success. Please wait...</p>
			}}
		}else{

		#}
		<form method="post" action="[path]auth/login">
			<div class="form-group">
				<label>Username</label>
				<input class="form-control" type="text" name="username" placeholder="Enter your username">
			</div>

			<div class="form-group">
				<label>Password</label>
				<input class="form-control" type="password" name="password" placeholder="Enter your password">
			</div>

			<div class="form-group">
				<button class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
	
</div>

';
						fwrite($page, $pcontent);
						fclose($page); 
				    }
				}

			    if(!file_exists("view/template/auth.cvf")){
			    	$temp = fopen("view/template/auth.cvf", "w");
			    	$tempcontent = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" >
    <title>{DEFAULT_TITLE}</title>
    <link rel="icon" href="[path]public/images/icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {DEFAULT_STYLE}
    <style>
	body{
		background-image: linear-gradient(180deg,#045c14,#32ea0c);
		background-attachment: 	fixed;
		height:100%;
	}
	.login-container{
		float:none;
		margin:auto;
	}

	div.login-container .wrapper{
		background-color:white;
		width:100%;
		float:left;
		padding:20pt;
		border-radius:5px;
		margin-top:20%;
	}

	header,footer{
		display:none;
	}

</style>

</head>
<body>
    <div id="content">
        {DEFAULT_BODY}              
    </div>
{DEFAULT_SCRIPT}
</body>

</html>
';
					fwrite($temp, $tempcontent);
					fclose($temp); 
			    }

				echo $this->response(["message"=>"AuthController.php successfully created","type"=>"success"],200);
			}else{
				echo $this->response(["message"=>"AuthController.php is already exist","type"=>"error"],200);
			}
	}


	public function getValues(){
		$c = $_POST["command"];

		if($c == "db"){
			$result = "Connection:".DB_CONNECTION.
					  " | Host:".DB_HOST.
					  " | Name:".DB_NAME.
					  " | Charset:".DB_CHARSET.
					  " | Username:".DB_USER.
					  " | Password:".DB_PASSWORD;
		}elseif($c == "dbconnection"){
			$result = DB_CONNECTION;
		}elseif($c == "dbhost"){
			$result = DB_HOST;
		}elseif($c == "dbname"){
			$result = DB_NAME;
		}elseif($c == "dbcharset"){
			$result = DB_CHARSET;
		}elseif($c == "dbusername"){
			$result = DB_USER;
		}elseif($c == "dbpassword"){
			$result = DB_PASSWORD;
		}elseif($c == "console"){
			$result = CONSOLE;
		}elseif($c == "rootfolder"){
			$result = ROOT_FOLDER;
		}elseif($c == "install key"){
			$result = APPLICATION_KEY;
		}elseif($c == "local"){
			$result = LOCAL;
		}elseif($c == "csserror"){
			$result = CSS_ERROR;
		}elseif($c == "jserror"){
			$result = JS_ERROR;
		}elseif($c == "imagesize"){
			$result = DEFAULT_IMAGE_SIZE;
		}elseif($c == "filesize"){
			$result = DEFAULT_FILE_SIZE;
		}elseif($c == "styles"){
			$def = "";
			$css_lib = explode(',',CSS_LIBRARY);  
               $checker = 0;
               if($css_lib[0] != ""){
                $checker++;
                 for($x=0;$x<count($css_lib);$x++){
                $def.='<li>'.$css_lib[$x].'.css</li>';
               }
           }
               $result = $def;
		}elseif($c == "scripts"){
			$def = "";
			$css_lib = explode(';',JS_LIBRARY);  
               $checker = 0;
               if($css_lib[0] != ""){
                $checker++;
                 for($x=0;$x<count($css_lib);$x++){
                $def.='<li>'.$css_lib[$x].'.js</li>';
               }
           }
               $result = $def;
		}elseif($c == "libraries"){
			$def = "";
			$js_lib = explode(';',CHKN_ASSETS);  
                        $checker = 0;
                        if($js_lib[0] != ""){
                            $checker++;
                            for($x=0;$x<count($js_lib);$x++){
                            $def.='<li>'.$js_lib[$x].'</li>';
                           } 
                        }
               $result = $def;
		}elseif($c == "status"){
			$def = "";
			if(strlen(APPLICATION_KEY) != 43){
            	$def.='
	                <li><div class="con salt_error">
	                <h3> <i class="fa fa-password"></i> Application Key: Not yet updated</h3>
	                <p>To Change it, Enter command `install key`</p><br>
	                </div></li>';
	        }else{
	            $def.='<li><div class="con salt_success">
	                <h3>  <i class="fa fa-user"> </i> Application Key: Installed</h3>
	                </div></li>';
	        }

	        if(QUERY_BUILDER == 1){
	        	$db = $this->CRUD->check_db();
				if($db[0] == 'Database Connection Error'){
		            $def.=
		                '<li><div class="con database_error">
		                <h3>Database Status: Not Found</h3>
		                <p>Check CHKN `db -help` command.</p><br>
		                </div></li>';
		        }else{
		            $def.=
		                '<li><div class="con database_success">
		                <h3> <i class="fa fa-database"></i> Database Status: Ready</h3>
		                </div></li>';
		        }
	        }else{
	        	$def .= "<li style='color:red'>QUERY_BUILDER is disabled</li>";
	        }
			
			$result = $def;

		}else{
			$result = "Unknown Command";
		}

		echo $this->response(["value"=>$result],200);
	}
}
<?php
/**CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 * Class process_model
 * This class holds the PDO Main function the CRUD
 *
 *
 */
class Model extends App_Model{
    protected $keys;
    protected $params = array();
    protected $key_values = array();
 
    /**
     * @return mixed
     * Checks if the database is already settled up and does'nt used the default value for the variables
     */
    public function check_db(){
        $response = $this->db_connect();
        return $response;
    }

    public function lastInsertId(){
        return $this->lastInsertedID();
    }

    /**
     * @param $sql
     * @return mixed
     * This function collects all the data needed by the user
     * It will automatically generate a SELECT query
     */
    public function get_list($query,$line,$indexes){
        try {
            $this->collect("====================================CRUD:SELECT====================================","info");
            $this->collect("QUERY: ".$query,"success");
            $this->db_connect();
            $this->db_prepare($query);
            foreach ($indexes as $key => $value) {
                $key = str_replace(".", "", $key);
                $this->db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            $data = $this->db_execute();
            $count = count($data);
            $this->collect("RECORD COUNT: ".$count,"success");
            $this->collect("====================================================================================","info");
            return $data;
        } catch (PDOException $e) {
           $this->builder($e,$query,$line,$type="where clause");
            $this->collect($e->getMessage(),"error");
            $this->collect("====================================================================================","info");
        }
    }

   
    /**
     * @param $sql
     * @return mixed
     * This function allows the user to add data to the database easier
     * It will automatically generate a INSERT query and VALUES that is based on the data gathered by $sql
     */
    public function add_query_execute($query,$line,$indexes){
        try {
            $this->collect("====================================CRUD:INSERT====================================","info");
            $this->collect("QUERY: ".$query,"success");
            $this->db_connect();
            
            $this->db_prepare($query);
            foreach ($indexes as $key => $value) {
                $this->db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            $this->db_execute();
            $this->collect("====================================================================================","info");

            $id = $this->lastInsertId();

            
        } catch (Exception $e) {
           $this->builder($e,$query,$line,$type="field list");
            $this->collect($e->getMessage(),"error");
            $this->collect("====================================================================================","info");
        }
        
    }

    /**
     * @param $sql
     * @return mixed
     * This function allows the user to delete data to the database easier
     * It will automatically generate a DELETE query that is based on the data gathered by $sql
     */
    public function delete_query_execute($query,$line,$indexes){
         try {
            $this->collect("====================================CRUD:DELETE====================================","info");
            $this->collect("QUERY: ".$query,"success");
            $this->db_connect();
            $this->db_prepare($query);
            foreach ($indexes as $key => $value) {
                $this->db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            $this->db_execute();
            $this->collect("====================================================================================","info");
        } catch (PDOException $e) {

           $this->builder($e,$query,$line,$type="where clause");
            $this->collect($e->getMessage(),"error");
            $this->collect("====================================================================================","info");
        }
    }

    /**
     * @param $sql
     * @return mixed
     * This function allows the user to update data to the database easier
     * It will automatically generate a UPDATE query that is based on the data gathered by $sql
     */
    public function update_query_execute($query,$line,$sets,$indexes){
        try {
            $this->collect("====================================CRUD:UPDATE====================================","info");
            $this->collect("QUERY: ".$query,"success");
            $this->db_connect();
            $this->db_prepare($query);
            foreach ($indexes as $key => $value) {
                $this->db_bind(':chknParam'.$key,$value);
            }
            foreach ($sets as $key => $value) {
                $this->db_bind(':chknParam'.$key,$value);
            }
            $this->db_execute();
            $this->collect("====================================================================================","info");
        } catch (PDOException $e) {
           $this->builder($e,$query,$line,$type="where clause");
            $this->collect($e->getMessage(),"error");
            $this->collect("====================================================================================","info");
        }
    }

    /**
     * @param $sql
     * @return mixed
     * This function allows the user to easily execute a set SQL QUERY
     */
    public function query($sql,$line){
        try {
            $this->db_connect();
            $query = $sql[0];
            $this->db_prepare($query);
            $count = substr_count($query,':param');
            for($x=0;$x<$count;$x++){$a = $x+1;$this->db_bind(':param'.$a,$sql[1][$x]);}
            $data = $this->db_execute();
            return $data;
        } catch (PDOException $e) {
           $this->builder($e,$query,$line,$type="where clause");
            $this->collect($e->getMessage(),"error");
            $this->collect("====================================================================================","info");
        }
    }

    /**
     * @param $table
     * @return mixed
     * Truncate or remove all the data on a table
     */
    public function truncate($table,$line){
        $this->db_connect();
        $query = 'TRUNCATE TABLE '.$table;
        $this->db_prepare($query);
        $response = $this->db_execute();
        return $response;
    }


    public function errorHand($message,$line){

    $server = explode('/',str_replace('url=', "", $_SERVER['QUERY_STRING']));
    $url = DEFAULT_URL.$server[0];
    if($url == DEFAULT_URL){
        $file = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']).'controller/index.php';
    }else{
        $file = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']).'controller/'.$server[0].'Controller.php';
    }

        $html = '<style>
                    .chkn_handler{
                        width:100%;
                        float:left;
                        background-color:#fff;
                        border:1px solid #ccc;
                    }

                    .chkn_handler_header{
                        padding:10pt;
                        float:left;
                        width:97.5%;
                        text-transform:uppercase;
                    }
                    .chkn_handler_header p{
                        padding:10pt;
                        margin:0;
                    }
                    .chkn_handler_error{
                        background-image:linear-gradient(to bottom, #ff3019 0%,#cf0404 100%);
                        color:white;
                        padding:10pt;
                    }
                    .chkn_handler_warning{
                        background-image:linear-gradient(to bottom, #ffa84c 0%,#ff7b0d 100%);;
                        color:black;
                    }
                    .chkn_handler_content{
                        width:97.5%;
                        padding:10pt;
                    }

                </style>';
        $html .= '<div class="chkn_handler">';
        $html .= '<div class="chkn_handler_error">PDO Exception</div>';
        $html .= '<div class="chkn_handler_content">';
        $html .= '
                <p><b>Message:</b> '.$message.'</p>
                <p><b>File Located:</b> '.$file.'</p>
                <p><b>Line:</b>'.$line.'</p>
        ';      
        $html.='</div>';
        $html .=  '</div>';
        echo $html;
        // $this->collect('<p><b>Message:</b> '.$message.'</p>
        //         <p><b>File Located:</b> '.$file.'</p>
        //         <p><b>Line:</b>'.$line.'</p>',"error");
    }

    public function builder($e,$tablename,$line,$type){
        switch ($e->getCode()) {
               case '42S02':
                $message = 'Table <span style="font-style:italic;color:red;">'.$tablename.'</span> doesn\'t exist';
               break;
               case '42000':
                $message = 'Syntax error or access violation. Check the CHKN Documentation for SELECT function.';    
               break;
               case '42S22':
               $cut = str_replace('SQLSTATE[42S22]: Column not found: 1054 Unknown column \'', '', $e->getMessage());
               $cut = str_replace('\' in \''.$type.'\'', '', $cut);
                $message = 'Unknown column <span style="font-style:italic;color:red;">'.$cut.'</span>';    
               break;
               default:
                $message = '';
                $search_query = '';
               break;   
           }
           $this->errorHand($message,$line);
    }

    protected function collect($value = "",$status=""){
        if(isset($_SESSION["console_collection"])){
            $count = count($_SESSION["console_collection"]);
            $_SESSION["console_collection"][$count]["message"] = $value;
            $_SESSION["console_trigger"] = 1;
            if($value == "Initialized Controller"){
                $_SESSION["console_collection"][$count]["message"] = "Initialized Index Controller";
            }
            $_SESSION["console_collection"][$count]["status"] = $status;
        }else{
            $_SESSION["console_collection"][0]["message"] = $value;
            $_SESSION["console_collection"][0]["status"] = $status;
            $_SESSION["console_trigger"] = 1;
            if($value == "Initialized Controller"){
                $_SESSION["console_collection"][$count]["message"] = "Initialized Index Controller";
            }

        }
        
    }
}

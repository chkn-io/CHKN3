<?php
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Settings Page
 *
 * Class global_helper
 * This class calls all the other helper and libraries requested by the user
 * It includes process_model,Mailer,sms_helper,encrypt_helper,upload_helper,download_helper,pdf_helper,excel_helper
 */
class CRUD {
	private $model;
    private $line;
    private $q;
    private $indexes = array();
    private $sets = array();
    private $index_counter = 0;
    private $trans;
    private $jointrace;
	function __construct(){
        if(QUERY_BUILDER == 1){
            $this->model = new Model;
        }
	}

    public function check_db(){
        if(QUERY_BUILDER == 1){
            $response = $this->model->check_db();
            return $response;
        }
    }

    public function lastInsertId(){
        if(QUERY_BUILDER == 1){
            return $this->model->lastInsertId();
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
        
    }

    public function join($table,$reference1,$comparison,$reference2){
        $this->q .=' INNER JOIN '.$table." ON ".$reference1." = ".$reference2." ";
        $this->jointrace = true;
        return $this;
    }

    public function select($table,$fields="*"){
        if(QUERY_BUILDER == 1){
            $this->index_counter = 0;
            $this->indexes = array();
            $this->jointrace = false;
            $this->q = "";
            $this->q = 'SELECT '.$fields.' FROM '.$table.' ';
            return $this;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
        
    }

    public function where($index,$operation,$value,$opt = 'and'){
        $this->indexes[$index] = $value;
        if($this->index_counter == 0){
            if($this->jointrace == false){
                $this->q .='WHERE '.$index.' '.$operation.' :chknParam'.$index.' ';
            }else{
                $trace = str_replace(".", "", $index);
                $this->q .='WHERE '.$index.' '.$operation.' :chknParam'.$trace.' ';
            }
            
        }else{
            if($this->jointrace == false){
                 $this->q .=$opt.' '.$index.' '.$operation.' :chknParam'.$index.' ';;
            }else{
                $trace = str_replace(".", "", $index);
                $this->q .=$opt.' '.$index.' '.$operation.' :chknParam'.$trace.' ';;
            }
        }
        $this->index_counter++;
        return $this;
    }

    public function bind($index,$value){
        $this->indexes[$index] = $value;
        return $this;
    }
    public function orderBy($index,$type){
        $this->q .=' ORDER BY '.$index.' '.$type;
        return $this;
    }

    public function limit($value){
        $this->q .=' LIMIT '.$value;
        return $this;
    }

    public function delete($table){
        if(QUERY_BUILDER == 1){
            $this->q = 'DELETE FROM '.$table.' ';
            $this->trans = 'delete';
            return $this;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }
    public function query($sql){
        if(QUERY_BUILDER == 1){
            $this->index_counter = 0;
            $this->q = $sql;
            $this->trans = "query";
            return $this;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }

    public function insert($table){
        if(QUERY_BUILDER == 1){
            $this->index_counter = 0;
            $this->q = 'INSERT INTO '.$table.' (';
            $this->trans = "insert";
            return $this;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }

    public function field($index,$value){
        $this->sets[$index] = $value;
        return $this;
    }
    
	public function fetch($method = "",$array = []){
        $bt = debug_backtrace();
        $this->line = $bt[0]['line'];
		$response = $this->model->get_list($this->q,$this->line,$this->indexes);
        if($method != ""){
            if($method == "remove"){
                foreach ($array as $v) {
                    foreach ($response as $key => $value) {
                        if(isset($response[$key][$v])){
                            unset($response[$key][$v]);
                        }
                    }
                }
            }
        }
        $this->indexes = array();
        $this->index_counter = 0;
		return $response;
	}

    public function execute(){
        $bt = debug_backtrace();
        $this->line = $bt[0]['line'];
        if($this->trans == "delete"){
            $response = $this->model->delete_query_execute($this->q,$this->line,$this->indexes);
        }elseif($this->trans == "insert"){
            $ind = '';
            $par = '';
            foreach ($this->sets as $key => $value) {
                $ind .=$key.',';
                $par .=':chknParam'.$key.',';
            }
            $ind = substr($ind,0,strlen($ind) - 1);
            $par = substr($par,0,strlen($par) - 1);
            $this->q .= $ind.') VALUES('.$par.')';
            $response = $this->model->add_query_execute($this->q,$this->line,$this->sets);
            $this->sets = array();
        }elseif($this->trans == "update"){
            $set = '';
            foreach ($this->sets as $key => $value) {
                $set .=$key.'='.':chknParam'.$key.',';
            }

            $set = substr($set,0,strlen($set) - 1);
            $this->q = str_replace('{SET_PARAMETER}', $set, $this->q);


            $response = $this->model->update_query_execute($this->q,$this->line,$this->sets,$this->indexes);
            $this->indexes = array();
            $this->index_counter = 0;
            $this->sets = array();
        }elseif($this->trans == "query"){
            $this->q = str_replace('{',':chknParam',$this->q);
            $this->q = str_replace('}','',$this->q);
            $response = $this->model->get_list($this->q,$this->line,$this->indexes);
            $this->indexes = array();
            $this->index_counter = 0;
        }
        return $response;
    }

    public function update($table){
        if(QUERY_BUILDER == 1){
        
            $this->index_counter = 0;
    		$this->q = 'UPDATE '.$table.' SET {SET_PARAMETER} ';
            $this->trans = "update";
            return $this;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
	}

    public function truncate($table){
        if(QUERY_BUILDER == 1){
            $bt = debug_backtrace();
            $this->line = $bt[0]['line'];
            $response = $this->model->truncate($table,$this->line);
            return $response;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }
 
}

<?php
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 * Class App_Model
 * This class holds the main connection to the set database
 * This class also holds the PDO main function
 * db_prepare() - prepares a SQL Query;
 * db_bind() - binds parameter that set on query
 * db_execute() - execute PDO request
 */

class App_Model{
		protected $dbconn;
		private $statement;
		protected function db_connect() {
            if(DB_HOST != '' && DB_NAME != '' && DB_USER != '' && DB_CONNECTION != ''){
                try {
                    $this->dbconn=new PDO(DB_CONNECTION.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET.'',''.DB_USER.'',''.DB_PASSWORD.'');
                    $this->dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }catch(PDOException $e){
                    $error = array();
                    $error[0] = 'Database Connection Error';
                    $error[1] = $e->getMessage();
                    return $error;
                }

            }else{
                $error = array();
                $error[0] = 'Database Connection Error';
                $error[1] = '';
                return $error;
            }

		}
		
		public function db_prepare($sql){
			
				$this->statement = $this->dbconn->prepare($sql);
				
		}
		
		protected function db_bind($param, $value) {
			$this->statement->bindParam($param, $value);
		}
		protected function db_execute() {
			$this->statement->execute();
			return $this->statement->fetchAll(PDO::FETCH_ASSOC);
		}

		protected function lastInsertedID(){
			return $this->dbconn->lastInsertId();
		}
}
<?php
class App_Session{
	
	function put($name,$value){
		$_SESSION[$name] = $value;
	}

	function get($name){
		return $_SESSION[$name];
		echo 1;
	}

	function check($name){
		if(isset($_SESSION[$name])){
			return true;
		}else{
			return false;
		}
	}

	function clear($name){
		unset($_SESSION[$name]);
	}

}
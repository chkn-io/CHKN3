<?php
class CSRFToken{
	public function init(){
		$string = str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP");
		$_SESSION["CSRFToken"] = $string;
		$_SESSION["CSRF"] = $string;
	}

	public function validator($form = ""){
		if(isset($_SESSION["CSRFToken"])){
			if($form == $_SESSION["CSRFToken"]){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	
}
	
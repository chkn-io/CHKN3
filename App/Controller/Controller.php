<?php

/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015		
 *
 * Class Controller
 * This class holds a function that will replace all the variable with {} inside a template and a page
 * This class also direct the system who will be showed to the browser
 */

class Controller extends App_Controller{
	function template($temp = ''){
		$this->path('view/template/'.$temp.".tpl");
		$this->assign('DEFAULT_PATH',DEFAULT_URL);
	}

	function con_interface($temp = ''){
		$this->path('view/defaults/'.$temp.".tpl");
		$this->assign('DEFAULT_PATH',DEFAULT_URL);
	}

	function title($title = ''){
		$this->assign('chkn:title',$title);
	}

	function css($css = ''){
		$this->assign('chkn:style',$this->view->Html_Objects('css',
            $css
		));
	}

	function js($js = ''){
		$this->assign('chkn:script',$this->view->Html_Objects('js',
			$js
		));
	}

	function body($path = ''){
		$data = $this->view->Html_Objects('page',$path.".cvf");
		$this->assign('chkn:body',$data);
	}

	function chkn_status(){
		$data = $this->helper->defaults();
		$this->assign('chkn:body',$data);
	}


	function show(){
        $this->assign('form:csrf','<input type="hidden" name="CSRFToken" value="'.$_SESSION["CSRF"].'">');
        $this->assign('chkn:csrf',$_SESSION["CSRF"]);
		$this->dispose();
	}

    function locate($url){
        header('location:'.DEFAULT_URL.$url);
    }

    function httpRequest($request){
    	$this->post = $request["post"];
    	$this->get = $request["get"];
    }

    function get($index){
    	return $this->get[$index];
    }

    function post($index,$position = 0){
    	return $this->post[$index][$position];

    }

    function response($info = [],$header){
    	header("HTTP/1.0 ".$header);
        header("Content-Type:application/json");
    	return json_encode($info);
    }

    function chknError(){
        $this->error->error_page();
    }

    function maintenance(){
    		$this->maintenance->maintenance_page();
    }

    function variable($_searchString, $_replacedString){
    	$this->pass_variable($_searchString, $_replacedString);
    }

    function array_var($key, $array){
    	$this->pass_array_var($key, $array);
    }

    function seal($value = ""){
    	$encrypt = $this->helper->encrypt($value);
    	return base64_encode($encrypt);
    }

    function rseal($value = ""){
    	$decode = base64_decode($value);
    	$decrypt = $this->helper->decrypt($decode);
    	return $decrypt;
    }

    function array_seal($array = array(),$field = ""){
    	for($x=0;$x<count($array);$x++){
    		$array[$x][$field] = $this->seal($array[$x][$field]);
    	}
    	return $array;
    }
}
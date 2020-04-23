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

class App_Controller{
	public $assignedValues = array();
  public $variable = array();
  public $array_var = array();
	public $tpl;
  public $helper;
  public $view;
  public $session;
  public $CRUD;
  public $error;
  public $maintenance;
  public $tem_tool;
  public $auth;
  public $csrf;

  protected $post;
   protected $get;

    /**
     * @param string $_path
     * A function that get the requested template
     */

    function __construct(){
        $this->helper = new global_helper;
        $this->view = new View;
        if(SESSION == 1){
          $this->session = new App_Session;
        }
        if(QUERY_BUILDER == 1){
          $this->CRUD = new CRUD;
        }
        if(PAGE_NOT_FOUND == 1){
           $this->error = new chknError;
        }
        if(MAINTENANCE_CLASS == 1){
           $this->maintenance = new maintenance;
        }
        if(CSRF == 1){
          $this->csrf = new CSRFToken;
        }

        $this->tem_tool = new CHKNTemplate;
        $this->auth = new Auth;
    }

	function path($_path = ''){
		if(!empty($_path)){
			if(file_exists($_path)){
				$this->tpl = file_get_contents($_path);	
			}else{
				$this->chknError();
			}
		}
  }
  
  function invalid_request(){
		header("HTTP/1.0 401");
		$file = file_get_contents(DEFAULT_URL."view/defaults/invalid_request.tpl");
		echo $file;
		exit;
	}

    /**
     * @param $_searchString
     * @param $_replacedString
     * This function is responsible for replacing variables with {} to its defined values
     */
    
	function assign($_searchString, $_replacedString){
		if(!empty($_searchString)){
			$this->assignedValues[strtolower($_searchString)] = $_replacedString;
		}
	}


  /**
     * @param $_searchString
     * @param $_replacedString
     * This function is responsible for replacing variables with {} to its defined values
     */
  function pass_variable($_searchString, $_replacedString){
    if(!empty($_searchString)){
      $this->variable[$_searchString] = $_replacedString;
    }
  }

  function pass_array_var($key, $array){
    $this->array_var[$key] = $array;
  }

    /**
     *This function executes the requested page(template,page,css,js,etc.)
     */

	function dispose(){
		if(count($this->assignedValues) > 0){
			foreach($this->assignedValues as $key => $value){
				$this->tpl = str_replace('['.$key.']',$value,$this->tpl);
			}

      foreach($this->variable as $key => $value){
        $this->tpl = str_replace('$'.$key.'',$value,$this->tpl);
      }

			$this->tpl = str_replace('[chkn:path]',DEFAULT_URL,$this->tpl);	
      preg_match('/#if(.*?)#}/s', $this->tpl,$result);
      if(count($result) != 0){
        $response = $this->tem_tool->if_condition($result);
        $this->tpl = str_replace($result[0], eval($response), $this->tpl);
      }

      preg_match('/#for(\\(.*?)#endfor/s', $this->tpl,$result);
      if(count($result) != 0){
       $return = $this->tem_tool->forloop($result);
        $this->tpl = str_replace($result[0], $return, $this->tpl);
      } 

      preg_match('/#foreach(\\(.*?)#endforeach/s', $this->tpl,$result);
      if(count($result) != 0){
        $return = $this->tem_tool->foreachs($this->tpl,$result,$this->array_var);
        
        $this->tpl = str_replace($result[0], $return, $this->tpl);
      }
      echo $this->tpl;
		}
	}


}
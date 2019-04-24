<?php
use App\App\Request;
class index extends Controller{
	public function index_page(Request $r){
		//Call index template
		$this->template('index');
		//set default title
		$this->title('CHKN Framework');
		//set css
		$this->css(array(
		));
		//set js
		$this->js(array(
		));

		$this->body('homepage/index');
		$this->show();
	}
}

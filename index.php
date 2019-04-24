<?php

date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL);
ini_set("display_errors", 0);
register_shutdown_function('fatalErrorShutdownHandler');
function fatalErrorShutdownHandler(){
	$last_error = error_get_last();
	require_once('App/Exception/catch.php');
	$catch = new Catcher;
	$catch->errorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
}
require_once('App/App/App_Loader.php');
require_once('App/Route/Router.php');
$loader = new Router();
$loader->Url_Controller();


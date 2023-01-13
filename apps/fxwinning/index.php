<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Backoffice;
$Layout->init(JFStudio\Router::getName($route),'index',"blank",'',TO_ROOT.'/');

$Layout->setScriptPath(HCStudio\Connection::getMainPath() . '/src/');
$Layout->setScript([
	'signaturePad.js',
	'autofxwinning.vue.js'
]);

$Layout->setVar([
	'route' =>  $route,
	'UserLogin' => $UserLogin
]);
$Layout();
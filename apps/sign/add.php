<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::FxWinning;
$Layout->init(JFStudio\Router::getName($route),'add',"sign",'',TO_ROOT.'/');

$Layout->setScriptPath(HCStudio\Connection::getMainPath() . '/src/');
$Layout->setScript([
	'signaturePad.js',
	'fxwinningsign.vue.js',
]);

$Layout->setVar([
	'route' =>  $route,
	'UserLogin' => $UserLogin
]);
$Layout();
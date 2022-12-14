<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === false) {
	HCStudio\Util::redirectTo(TO_ROOT."/apps/login/");
}

$UserLogin->checkRedirection();

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Range;
$Layout->init(JFStudio\Router::getName($route),'range',"backoffice",'',TO_ROOT.'/');

$UserPlan = new GranCapital\UserPlan;
$UserPlan->setPlan(1);

$Layout->setScriptPath(TO_ROOT . '/src/');
$Layout->setScript([
	'range.css',
	'range.vue.js'
]);

$Layout->setVar([
	'route' =>  $route,
	'UserLogin' => $UserLogin
]);
$Layout();
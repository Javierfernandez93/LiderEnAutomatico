<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === false) {
	HCStudio\Util::redirectTo(TO_ROOT."/apps/login/");
}

$UserLogin->checkRedirection();

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Invoice;
$Layout->init(JFStudio\Router::getName($route),'Invoice',"backoffice",'',TO_ROOT.'/');


// @TODO-invoice 
// d(json_encode(
//     [
//         'bank'=>'joirney',
//         'account'=>'123091409123',
//         'clabe'=>'12391023901293',
//         'beneficiary'=>'JhonSmith',
//     ]
// ));

$Layout->setScriptPath(TO_ROOT . '/src/');
$Layout->setScript([
	'invoice.vue.js'
]);

$Layout->setVar([
	'route' =>  $route,
	'UserLogin' => $UserLogin
]);
$Layout();
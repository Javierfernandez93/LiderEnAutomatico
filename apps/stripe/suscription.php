<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Stripe;
$Layout->init(JFStudio\Router::getName($route),"suscription","backoffice","",TO_ROOT."/");

$UserLogin = new GranCapital\UserLogin;

$Layout->setScriptPath(TO_ROOT . '/src/');
$Layout->setScript([
	'stripeSuscription.vue.js',
]);

// d(json_decode('{"id":"evt_1LX5sqHwPw0UyNibQSsqoHcx","object":"event","api_version":"2020-03-02","created":1660579880,"data":{"object":{"id":"clock_1LX5n2HwPw0UyNibuaZxwUkp","object":"test_helpers.test_clock","created":1660579520,"deletes_after":1663171520,"frozen_time":1660583534,"livemode":false,"name":"ABC","status":"ready"}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"test_helpers.test_clock.ready"}'));

$Layout->setVar([
	"route" => $route,
	"UserLogin" => $UserLogin
]);
$Layout();
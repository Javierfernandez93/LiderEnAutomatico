<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Stripe;
$Layout->init(JFStudio\Router::getName($route),"index","backoffice","",TO_ROOT."/");

$UserLogin = new GranCapital\UserLogin;

$Layout->setScriptPath(TO_ROOT . '/src/');
$Layout->setScript([
	'stripe.vue.js',
]);
// require_once TO_ROOT . '/vendor3/autoload.php';

// $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

// $pm = $stripe->paymentIntents->retrieve(
// 	'pi_3LYJzJHwPw0UyNib1zOT20La',
// 	[]
// );

// d($pm->payment_method);

$Layout->setVar([
	"route" => $route,
	// "buy_per_user_login_id" => HCStudio\Util::getVarFromPGS('bpulid'),
	// "BuyPerUser" => (new GranCapital\BuyPerUser),
	"UserLogin" => $UserLogin
]);
$Layout();
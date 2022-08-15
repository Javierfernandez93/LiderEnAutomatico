<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    require_once TO_ROOT . 'vendor3/autoload.php';

    $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    $subscription = $stripe->subscriptions->create([
        'customer' => $data['customer_id'],
        'items' => [
            [
                'price_data' => [
                    'unit_amount' => JFStudio\Stripe::formatAmmount($data['ammount']),
                    'currency' => $data['currency'],
                    'product' => 'prod_MErd84W3tsYRMg',
                    'recurring' => ['interval' => $data['interval']],
                ],
            ],
        ],
		'payment_behavior' => 'default_incomplete', 
		'expand' => ['latest_invoice.payment_intent'], 
    ]);

	if($subscription)
	{
		$data['subscriptionId'] = $subscription->id;
		$data['client_secret'] = $subscription->latest_invoice->payment_intent->client_secret;
		$data['s'] = 1;
		$data['r'] = 'DATA_OK';
	} else {
		$data['s'] = 0;
		$data['r'] = 'NOT_SUBSCRIPTION';	
	}
} else {
    $data['s'] = 0;
    $data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode($data);
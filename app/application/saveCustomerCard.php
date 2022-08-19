<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    require_once TO_ROOT . '/vendor3/autoload.php';

    $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    $paymentMethod = $stripe->paymentIntents->retrieve(
        $data['id'],
        []
    );

    if($paymentMethod)
    {
        $attachment = $stripe->paymentMethods->attach(
            $paymentMethod->payment_method,
            ['customer' => $data['customer_id']]
        );

        $update = $stripe->customers->update(
            $data['customer_id'],
            [   
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod->payment_method
                ]
            ]
        );

        $data['s'] = 1;
        $data['r'] = 'DATA_OK';
    } else {
        $data['s'] = 0;
        $data['r'] = 'NOT_PAYMENT_METHOD';
    }
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    require_once TO_ROOT . 'vendor3/autoload.php';

    $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    $product = $stripe->products->create([
        'name' => 'Recurring plan',
            'default_price_data' => [
            'unit_amount' => JFStudio\Stripe::formatAmmount(1000),
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
        ],
        'expand' => ['default_price'],
    ]);

    d($product);
    // 'prod_MEro1XfX4rxB8s'

    // $price = $stripe->prices->create([
    //     'product' => 'prod_MErd84W3tsYRMg',
    //     'unit_amount' => JFStudio\Stripe::formatAmmount(500),
    //     'currency' => 'usd',
    //     'recurring' => ['interval' => 'month'],
    // ]);

    // $paymentMethod = $stripe->paymentMethods->create([
    //     'type' => 'card',
    //     'card' => [
    //         'number' => '4242424242424242',
    //         'exp_month' => 8,
    //         'exp_year' => 2023,
    //         'cvc' => '314',
    //     ],
    // ]);

    // d($paymentMethod); // pm_1LWOEkHwPw0UyNibRgr5oCmW

    $suscription = $stripe->subscriptions->create([
        'customer' => 'cus_MEefUKZvKHZIY8',
        'items' => [
            [
                'price_data' => [
                    'unit_amount' => 5000,
                    'currency' => 'usd',
                    'product' => 'prod_MErd84W3tsYRMg',
                    'recurring' => ['interval' => 'month'],
                ],
            ],
        ],
    ]);

    d($suscription);

    // $paymentMethods = $stripe->customers->allPaymentMethods(
    //     'cus_MEefUKZvKHZIY8',
    //     ['type' => 'card']
    // );

    // d($paymentMethods);

    // $res = $stripe->paymentMethods->attach(
    //     'pm_1LWOEkHwPw0UyNibRgr5oCmW',
    //     ['customer' => 'cus_MEefUKZvKHZIY8']
    // );

    // d($res);
    


    // require_once TO_ROOT . 'system/lib/Stripe/init.php';

    // \Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    // $product = \Stripe\Product::create([ 
    //     'name' => 'Basic Dashboard',
    //         'default_price_data' => [
    //         'unit_amount' => JFStudio\Stripe::formatAmmount(1000),
    //         'currency' => 'usd',
    //         'recurring' => ['interval' => 'month'],
    //     ],
    //     'expand' => ['default_price'],
    // ]);

    // d($product);
    
    // if($product)
    // {
        
    // } else {
    //     $data['s'] = 0;
    //     $data['r'] = 'NOT_CUSTOMER';    
    // }
} else {
    $data['s'] = 0;
    $data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode($data);
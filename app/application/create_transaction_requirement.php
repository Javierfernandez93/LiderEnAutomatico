<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['ammount'])
    {
        if($data['catalog_payment_method'])
        {
            $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
            $TransactionRequirementPerUser->user_login_id = $UserLogin->company_id;
            $TransactionRequirementPerUser->ammount = $data['ammount'];
            $TransactionRequirementPerUser->fee = GranCapital\CatalogPaymentMethod::getFee((float)$data['catalog_payment_method']['fee'],(float)$data['ammount']);
            $TransactionRequirementPerUser->catalog_payment_method_id = $data['catalog_payment_method']['catalog_payment_method_id'];
            $TransactionRequirementPerUser->create_date = time();
            
            if($TransactionRequirementPerUser->save())
            {
                if((new GranCapital\CatalogPaymentMethod)->isCrypto($data['catalog_payment_method']['catalog_payment_method_id']))
                {
                    $data['transaction_requirement_per_user_id'] = $TransactionRequirementPerUser->getId();
                    $data['code'] = (new GranCapital\CatalogPaymentMethod)->getCode($data['catalog_payment_method']['catalog_payment_method_id']);

                    if($checkoutData = createTransaction($UserLogin->email,$data))
                    {
                        $TransactionRequirementPerUser->txn_id = $checkoutData['txn_id'];
                        $TransactionRequirementPerUser->checkout_data = json_encode($checkoutData);
                        
                        if($TransactionRequirementPerUser->save())
                        {
                            $data["checkoutData"] = $checkoutData;
                            $data["s"] = 1;
                            $data["r"] = "DATA_OK";
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_UPDATE";
                        }
                    } else {
                        $data["s"] = 0;
                        $data["r"] = "NOT_SAVE";
                    }
                } else {
                    if($data['catalog_payment_method']['catalog_payment_method_id'] == GranCapital\CatalogPaymentMethod::PAYPAL)
                    {
                        if($checkoutData = createTransactionPayPal($TransactionRequirementPerUser,$data['catalog_payment_method']))
                        {
                            $TransactionRequirementPerUser->txn_id = $checkoutData['txn_id']; // transaction id
                            $TransactionRequirementPerUser->checkout_data = json_encode($checkoutData);
                            
                            if($TransactionRequirementPerUser->save())
                            {
                                $data["checkoutData"] = $checkoutData;
                                $data["s"] = 1;
                                $data["r"] = "PAYPAL";
                            } else {
                                $data["s"] = 0;
                                $data["r"] = "NOT_PAYPAL_UPDATED";
                            }
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_PAYPAL_DATA";
                        }
                    } else if(in_array($data['catalog_payment_method']['catalog_payment_method_id'],[GranCapital\CatalogPaymentMethod::STRIPE,GranCapital\CatalogPaymentMethod::STRIPE_USA])) {
                        if($checkoutData = createTransactionStripe($TransactionRequirementPerUser))
                        {
                            $TransactionRequirementPerUser->txn_id = $checkoutData['txn_id']; // transaction id
                            $TransactionRequirementPerUser->checkout_data = json_encode($checkoutData);
                            
                            if($TransactionRequirementPerUser->save())
                            {
                                if(GranCapital\CatalogPaymentMethod::STRIPE)
                                {
                                    $ApiFixer = JFStudio\ApiFixer::getInstance();
                                    $checkoutData["ammount_to_add"] = $ApiFixer->convert($data['ammount'],'mxn','usd');
                                }

                                $data["checkoutData"] = $checkoutData;
                                $data["s"] = 1;
                                $data["r"] = "STRIPE";
                            } else {
                                $data["s"] = 0;
                                $data["r"] = "NOT_STRIPE_UPDATED";
                            }
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_STRIPE_DATA";
                        }
                    } else if(in_array($data['catalog_payment_method']['catalog_payment_method_id'],[GranCapital\CatalogPaymentMethod::TRANSFER_MXN,GranCapital\CatalogPaymentMethod::TRANSFER_USD,GranCapital\CatalogPaymentMethod::TRANSFER_COP])) {
                        if($checkoutData = createTransactionTransfer($TransactionRequirementPerUser))
                        {
                            // $TransactionRequirementPerUser->txn_id = $checkoutData['txn_id']; // transaction id
                            $TransactionRequirementPerUser->checkout_data = json_encode($checkoutData);

                            if(in_array($data['catalog_payment_method']['catalog_payment_method_id'],[GranCapital\CatalogPaymentMethod::TRANSFER_COP,GranCapital\CatalogPaymentMethod::TRANSFER_MXN]))
                            {
                                $ApiFixer = JFStudio\ApiFixer::getInstance();
                                $checkoutData["ammount_to_add"] = $ApiFixer->convert($data['ammount'],strtolower($data['catalog_payment_method']['currency']),'usd');
                            }
                            
                            if($TransactionRequirementPerUser->save())
                            {
                                $data["checkoutData"] = $checkoutData;
                                $data["s"] = 1;
                                $data["r"] = "STRIPE";
                            } else {
                                $data["s"] = 0;
                                $data["r"] = "NOT_STRIPE_UPDATED";
                            }
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_STRIPE_DATA";
                        }
                    }
                }
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_CATALOG_CURRENCY_ID";    
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_CATALOG_PAYMENT_METHOD";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_AMMOUNT";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

function createTransactionTransfer(GranCapital\TransactionRequirementPerUser $TransactionRequirementPerUser = null) : array
{
    return [
        'link' => "../../apps/wallet/invoice?trpid={$TransactionRequirementPerUser->getId()}",
        'txn_id' => $TransactionRequirementPerUser->getId(),
        'total' => $TransactionRequirementPerUser->ammount
    ];
}

function createTransactionStripe(GranCapital\TransactionRequirementPerUser $TransactionRequirementPerUser = null) : array
{
    $total = $TransactionRequirementPerUser->ammount+$TransactionRequirementPerUser->fee; 

    return [
        'link' => "../../apps/stripe/?trpid={$TransactionRequirementPerUser->getId()}",
        'linkSuscription' => "../../apps/stripe/suscription?trpid={$TransactionRequirementPerUser->getId()}",
        'txn_id' => $TransactionRequirementPerUser->getId(),
        'fee' => $TransactionRequirementPerUser->fee,
        'total' => $total
    ];
}

function createTransactionPayPal(GranCapital\TransactionRequirementPerUser $TransactionRequirementPerUser = null,array $catalog_payment_method = null)
{
	require_once TO_ROOT . "/system/vendor/autoload.php";

	$apiContext = new \PayPal\Rest\ApiContext(
	    new \PayPal\Auth\OAuthTokenCredential(
	        JFStudio\PayPal::CLIENT_ID,
	        JFStudio\PayPal::CLIENT_SECRET
	    )
	);

	$apiContext->setConfig(['mode' => JFStudio\PayPal::MODE]);

	$payer = new \PayPal\Api\Payer;
	
    $payer->setPaymentMethod('paypal');

    $total = $TransactionRequirementPerUser->ammount+$TransactionRequirementPerUser->fee; 
	
    $amount = new \PayPal\Api\Amount;
	$amount->setTotal((string)$total);
	$amount->setCurrency($catalog_payment_method['currency']);
	$amount->setDetails($details);

	$transaction = new \PayPal\Api\Transaction;
	$transaction->setAmount($amount);
    $transaction->setInvoiceNumber($TransactionRequirementPerUser->getId());
    
	$redirectUrls = new \PayPal\Api\RedirectUrls;
	$redirectUrls->setReturnUrl(JFStudio\PayPal::RETURN_URL)
	    ->setCancelUrl(JFStudio\PayPal::CANCEL_URL);

	$payment = new \PayPal\Api\Payment;
	$payment->setIntent('sale')
	    ->setPayer($payer)
	    ->setTransactions(array($transaction))
	    ->setRedirectUrls($redirectUrls);

	try {
	    $payment->create($apiContext);
	} catch (\PayPal\Exception\PayPalConnectionException $ex) {
	    echo $ex->getData();
	}

    return [
        'link' => $payment->getApprovalLink(),
        'txn_id' => $payment->getId(),
        'fee' => $TransactionRequirementPerUser->fee,
        'total' => $total
    ];
}

function createTransaction(string $email = null,array $data = null)
{
    require_once TO_ROOT .'/vendor2/autoload.php';

    try {
        $cps_api = new CoinpaymentsAPI(CoinPayments\Api::private_key, CoinPayments\Api::public_key, 'json');

        $req = [
            'amount' => $data['ammount'],
            'currency1' => 'USD',
            'currency2' => $data['code'],
            'buyer_email' => $email,
            'item_name' => 'Fondos en Libertad en Automático',
            'item_number' => (string)$data['transaction_requirement_per_user_id'],
            'address' => '', // leave blank send to follow your settings on the Coin Settings page
            'ipn_url' => 'https://grancapital.fund/ipn_handler.php',
        ];
        // See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
                
        $result = $cps_api->CreateCustomTransaction($req);
        
        if ($result['error'] == 'ok') {

            return $result['result'];
        } else {
            print 'Error: '.$result['error']."\n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
}

echo json_encode($data);
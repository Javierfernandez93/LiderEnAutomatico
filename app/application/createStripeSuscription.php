<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['transaction_requirement_per_user_id'])
    {
        $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
        
        if($TransactionRequirementPerUser->cargarDonde('transaction_requirement_per_user_id = ?',$data['transaction_requirement_per_user_id']))
        {
            require_once TO_ROOT . 'vendor3/autoload.php';

            $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

            try {
                $subscription = $stripe->subscriptions->create([
                    'customer' => $data['customer_id'],
                    'items' => [
                        [
                            'price_data' => [
                                'unit_amount' => JFStudio\Stripe::formatAmmount($data['ammount']),
                                'currency' => $data['currency'],
                                'product' => JFStudio\Stripe::getProductIdByCurreny($data['currency']),
                                'recurring' => ['interval' => $data['interval']],
                            ],
                        ],
                    ],
                    'payment_behavior' => 'default_incomplete', 
                    'expand' => ['latest_invoice.payment_intent'], 
                ]);

                if($subscription)
                {
                    $TransactionRequirementPerUser->txn_id = $subscription->latest_invoice->payment_intent->id;

                    if($TransactionRequirementPerUser->save())
                    {
                        $data['subscriptionId'] = $subscription->id;
                        $data['client_secret'] = $subscription->latest_invoice->payment_intent->client_secret;
                        $data['s'] = 1;
                        $data['r'] = 'DATA_OK';
                    } else {
                        $data['s'] = 0;
                        $data['r'] = 'NOT_UPDATING_TRANSACTION';	
                    }
                } else {
                    $data['s'] = 0;
                    $data['r'] = 'NOT_SUBSCRIPTION';	
                }
            } catch (Error $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }

        } else {
            $data['s'] = 0;
            $data['r'] = 'NOT_TRANSACTION_REQUIREMENT_PER_USER';
        }    
    } else {
        $data['s'] = 0;
        $data['r'] = 'NOT_TRANSACTION_REQUIREMENT_PER_USER_ID';
    }   
} else {
    $data['s'] = 0;
    $data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode($data);
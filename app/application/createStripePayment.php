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
                $paymentIntent = $stripe->paymentIntents->create([
                    'amount' => JFStudio\Stripe::formatAmmount($data['ammount']),
                    'currency' => $data['currency'],
                    'payment_method_types' => ['card'],
                    'setup_future_usage' => 'off_session',
                    'description' => 'GranCapital-'.$TransactionRequirementPerUser->getId()
                ]);

                if($paymentIntent)
                {
                    $TransactionRequirementPerUser->txn_id = $paymentIntent->id;
                    
                    if($TransactionRequirementPerUser->save())
                    {
                        $data['client_secret'] = $paymentIntent->client_secret;
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
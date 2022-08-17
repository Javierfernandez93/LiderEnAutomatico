<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	if($data['id'])
	{
		require_once TO_ROOT . 'vendor3/autoload.php';

		$stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

        $paymentIntent = $stripe->paymentIntents->retrieve($data['id']);
    
		if($paymentIntent->status === JFStudio\Stripe::APPROVED)
		{
            $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
            
            if($TransactionRequirementPerUser->cargarDonde("txn_id = ? AND status = ?",[$paymentIntent->id,GranCapital\TransactionRequirementPerUser::PENDING]))
            {
                $Curl = new JFStudio\Curl;
                $Curl->get(HCStudio\Connection::getMainPath()."/app/application/apply_deposit.php",[
                    'user' => HCStudio\Util::$username,
                    'password' => HCStudio\Util::$password,
                    'transaction_requirement_per_user_id' => $TransactionRequirementPerUser->getId()
                ]);

                if($response = $Curl->getResponse(true))
                {
                    $data['response'] = $response;

                    if($response['s'] == 1)
                    {
                        $data['s'] = 1;
                        $data['r'] = 'DATA_OK';
                    } else {
                        $data['s'] = 0;
                        $data['r'] = 'NOT_SUCCESS';
                    }
                } else {
                    $data['s'] = 0;
                    $data['r'] = 'NOT_RESPONSE';
                } 
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_TRANSACTION_REQUIREMENT_PER_USER";  
            }
		} else {
			$data['r'] = 'NOT_SUCCESS';
			$data['s'] = 0;
		}
	} else {
		$data['s'] = 0;
		$data['r'] = 'NOT_ID';
	}
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
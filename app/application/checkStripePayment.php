<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	if($data['id'])
	{
		require_once TO_ROOT . 'system/lib/Stripe/init.php';

		\Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

		$stripe_response = \Stripe\PaymentIntent::retrieve(
		  $data['id']
		);

		if($stripe_response['status'] === JFStudio\Stripe::APPROVED)
		{
			$Stripe = JFStudio\Stripe::getInstance();

            // UPDATE TRANSACTION_REQUIREMENT
            $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
            
            if($TransactionRequirementPerUser->cargarDonde("txn_id = ? AND status = ?",[$stripe_response->id,GranCapital\TransactionRequirementPerUser::PENDING]))
            {
                $UserWallet = new GranCapital\UserWallet;
                            
                if($UserWallet->getSafeWallet($TransactionRequirementPerUser->user_login_id))
                {
                    $ammount = $TransactionRequirementPerUser->ammount;

                    $currency = (new GranCapital\CatalogPaymentMethod)->getCurrency($TransactionRequirementPerUser->catalog_payment_method_id);
                    
                    if($currency != GranCapital\TransactionRequirementPerUser::DEFAULT_CURRENCY)
                    {
                        $ApiFixer = JFStudio\ApiFixer::getInstance();
                        $ammount = $ApiFixer->convert($ammount,strtolower($currency),strtolower(GranCapital\TransactionRequirementPerUser::DEFAULT_CURRENCY));
                    }

                    if($UserWallet->doTransaction($ammount,GranCapital\Transaction::DEPOSIT,null,null,false))
                    {
                        $UserPlan = new GranCapital\UserPlan;

                        if($UserPlan->setPlan($UserWallet->user_login_id))
                        {
                            if(updateTransaction($TransactionRequirementPerUser->getId()))
                            {
                                $data["s"] = 1;
                                $data["r"] = "PAYMENT_PROCCESS_OK";
                            }  else {
                                $data["s"] = 0;
                                $data["r"] = "NOT_UPDATE_TRANSACTION";    
                            }
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_UPDATE_PLAN";
                        }
                    } else {
                        $data['r'] = "NOT_WALLET";
                        $data['s'] = 0;
                    }
                } else {
                    $data['r'] = "DATA_ERROR";
                    $data['s'] = 0;
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

function updateTransaction(int $transaction_requirement_per_user_id = null)
{
    if(isset($transaction_requirement_per_user_id) === true)
    {
        $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
        
        if($TransactionRequirementPerUser->isPending($transaction_requirement_per_user_id))
        {   
            if($TransactionRequirementPerUser->cargarDonde("transaction_requirement_per_user_id = ?",$transaction_requirement_per_user_id))
            {
                $TransactionRequirementPerUser->status = GranCapital\TransactionRequirementPerUser::VALIDATED;
                $TransactionRequirementPerUser->validate_date = time();
                $TransactionRequirementPerUser->validation_method = GranCapital\TransactionRequirementPerUser::STRIPE_CDN;

                return $TransactionRequirementPerUser->save();
            }
        }
    }
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
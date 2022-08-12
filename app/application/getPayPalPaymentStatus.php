<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['paymentId'])
    {
        if($data['PayerID'])
        {
            if($payment = getPayment($data['paymentId'],$data['PayerID']))
            {
                if($payment->getState() == JFStudio\PayPal::APPROVED)
                {
                    $data['transaction_requirement_per_user_id'] = $payment->getTransactions()[0]->getInvoiceNumber();
                    // UPDATE TRANSACTION_REQUIREMENT
                    $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
                    
                    if($TransactionRequirementPerUser->cargarDonde("transaction_requirement_per_user_id = ? AND status = ?",[$data['transaction_requirement_per_user_id'],GranCapital\TransactionRequirementPerUser::PENDING]))
                    {
                        $UserWallet = new GranCapital\UserWallet;
                                    
                        if($UserWallet->getSafeWallet($TransactionRequirementPerUser->user_login_id))
                        {
                            if($UserWallet->doTransaction($TransactionRequirementPerUser->ammount,GranCapital\Transaction::DEPOSIT,null,null,false))
                            {
                                $UserPlan = new GranCapital\UserPlan;

                                if($UserPlan->setPlan($UserWallet->user_login_id))
                                {
                                    if(updateTransaction($TransactionRequirementPerUser->getId()))
                                    {
                                        $data["s"] = 1;
                                        $data["r"] = "DATA_OK";
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
                    $data["s"] = 0;
                    $data["r"] = "NOT_APPROVED";
                }
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_PAYMENT";
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_PAYER_ID";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_PAYMENT_ID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
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
                $TransactionRequirementPerUser->validation_method = GranCapital\TransactionRequirementPerUser::PAYPAL_CDN;

                return $TransactionRequirementPerUser->save();
            }
        }
    }
}

function getPayment(string $paymentId = null,string $PayerID = null)
{
    require_once TO_ROOT . "/system/vendor/autoload.php";

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            JFStudio\PayPal::CLIENT_ID,
            JFStudio\PayPal::CLIENT_SECRET
        )
    );

    $apiContext->setConfig(['mode' => JFStudio\PayPal::MODE]);

    $payment = PayPal\Api\Payment::get($paymentId, $apiContext);
    $execution = new \PayPal\Api\PaymentExecution();
    $execution->setPayerId($PayerID);

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');

    try {
        $payment->execute($execution, $apiContext);
    } catch (Exception $e) {

    }

    return $payment;
}

echo json_encode($data);
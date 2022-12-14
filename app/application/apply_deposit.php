<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getVarFromPGS();

$UserSupport = new GranCapital\UserSupport;

if(($data['user'] == HCStudio\Util::$username && $data['password'] == HCStudio\Util::$password) || $UserSupport->_loaded === true)
{
    if($data['transaction_requirement_per_user_id'])
    {
        $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
        
        if($TransactionRequirementPerUser->isPending($data['transaction_requirement_per_user_id']))
        {
            if($TransactionRequirementPerUser->cargarDonde('transaction_requirement_per_user_id = ?',$data['transaction_requirement_per_user_id']))
            {
                $TransactionPerWallet = new GranCapital\TransactionPerWallet;

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
                        $user_support_id = 0;
                        $validation_method = $data['validation_method'];
                        
                        if($UserSupport->getId())
                        {
                            $user_support_id = $UserSupport->getId();
                            $validation_method = GranCapital\TransactionRequirementPerUser::ADMIN;
                        }
                        
                        if(updateTransaction($data['transaction_requirement_per_user_id'],$user_support_id,$validation_method))
                        {
                            $UserPlan = new GranCapital\UserPlan;

                            if($UserPlan->setPlan($UserWallet->user_login_id))
                            {
                                // if(sendPush($TransactionRequirementPerUser->user_login_id,'Hemos fondeado tu cuenta',GranCapital\CatalogNotification::ACCOUNT))
                                // {
                                //     $data["push_sent"] = true;
                                // }

                                // if(sendEmail($UserSupport->getUserEmail($TransactionRequirementPerUser->user_login_id),$TransactionRequirementPerUser->ammount))
                                // {
                                //     $data["email_sent"] = true;
                                // }

                                $data["s"] = 1;
                                $data["r"] = "DATA_OK";
                            } else {
                                $data["s"] = 0;
                                $data["r"] = "NOT_UPDATE_PLAN";
                            }
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "TRANSACTION_NOT_UPDATED";
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
                $data['r'] = "NOT_TRANSACTION_REQUIREMENT_PER_USER";
                $data['s'] = 0;
            }
        } else {
            $data['r'] = "IS_NOT_PENDING";
            $data['s'] = 0;
        }
    } else {
        $data['r'] = "NOT_TRANSACTION_REQUIREMENT_PER_USER_ID";
        $data['s'] = 0;
    }
} else {
    $data['s'] = 0;
    $data['r'] = "INVALID_CREDENTIALS";
}

function updateTransaction(int $transaction_requirement_per_user_id = null,int $user_support_id = null,int $validation_method = null) : bool
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
                $TransactionRequirementPerUser->validation_method = $validation_method;
                $TransactionRequirementPerUser->user_support_id = $user_support_id;

                return $TransactionRequirementPerUser->save();
            }
        }
    }

    return false;
}

function sendPush(string $user_login_id = null,string $message = null,int $catalog_notification_id = null) : bool
{
    return GranCapital\NotificationPerUser::push($user_login_id,$message,$catalog_notification_id,"");
}

function sendEmail(string $email = null,float $ammount = null) : bool
{
    if(isset($email,$ammount) === true)
    {
        require_once TO_ROOT . '/vendor/autoload.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $Layout = JFStudio\Layout::getInstance();
            $Layout->init("",'depositApplied',"mail",TO_ROOT.'/apps/applications/',TO_ROOT.'/');

            $Layout->setScriptPath(TO_ROOT . '/apps/admin/src/');
    		$Layout->setScript(['']);

            $CatalogMailController = GranCapital\CatalogMailController::init(1);

            $Layout->setVar([
                "email" => $email,
                "ammount" => $ammount
            ]);

            $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_OFF; // PHPMailer\PHPMailer\SMTP::DEBUG_SERVER
            $mail->isSMTP(); 
            // $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host = $CatalogMailController->host;
            $mail->SMTPAuth = true; 
            $mail->Username = $CatalogMailController->mail;
            $mail->Password =  $CatalogMailController->password;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = $CatalogMailController->port; 

            //Recipients
            $mail->setFrom($CatalogMailController->mail, $CatalogMailController->sender);
            $mail->addAddress($email, 'Usuario');     

            //Content
            $mail->isHTML(true);                                  
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Libertad en Autom??tico - Fondos Aplicados';
            $mail->Body = $Layout->getHtml();
            $mail->AltBody = strip_tags($Layout->getHtml());

            return $mail->send();
        } catch (Exception $e) {
            
        }
    }

    return false;
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
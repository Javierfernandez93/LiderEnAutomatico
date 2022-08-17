<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['withdraw_per_user_id'])
    {
        $WithdrawPerUser = new GranCapital\WithdrawPerUser;

        if($WithdrawPerUser->cargarDonde('withdraw_per_user_id = ?',$data['withdraw_per_user_id']))
        {
            $TransactionPerWallet = new GranCapital\TransactionPerWallet;
            
            if($TransactionPerWallet->cargarDonde('transaction_per_wallet_id = ?',$WithdrawPerUser->transaction_per_wallet_id))
            {
                $WithdrawPerUser->status = GranCapital\WithdrawPerUser::DELETED;
                
                if($WithdrawPerUser->save())
                {
                    $TransactionPerWallet->status = GranCapital\TransactionPerWallet::DELETED;
                    
                    if($TransactionPerWallet->save())
                    {
                        $UserPlan = new GranCapital\UserPlan;

                        if($UserPlan->setPlan($data['user_login_id']))
                        {
                            $data["s"] = 1;
                            $data["r"] = "UPDATE_OK";
                        } else {
                            $data["s"] = 0;
                            $data["r"] = "NOT_UPDATE_PLAN";
                        }
                    } else {
                        $data["s"] = 0;
                        $data["r"] = "NOT_SAVE";
                    }
                } else {
                    $data["s"] = 0;
                    $data["r"] = "NOT_SAVE";
                }
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_TRANSACTION_PER_WALLET";
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_WITHDRAW_PER_USER";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_WITHDRAW_PER_USER_ID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
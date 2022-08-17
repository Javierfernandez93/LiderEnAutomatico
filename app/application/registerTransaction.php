<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['transaction_requirement_per_user_id'])
    {
        $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
        
        if($TransactionRequirementPerUser->isPendingForRegistration($data['transaction_requirement_per_user_id']))
        {
            if($TransactionRequirementPerUser->cargarDonde('transaction_requirement_per_user_id = ?',$data['transaction_requirement_per_user_id']))
            {
                $TransactionRequirementPerUser->image = $data['image'];
                $TransactionRequirementPerUser->payment_reference = $data['payment_reference'];
                $TransactionRequirementPerUser->registration_date = time();

                if($TransactionRequirementPerUser->save())
                {
                    $data['s'] = 1;
                    $data['r'] = 'DATA_OK';
                } else {
                    $data['s'] = 0;
                    $data['r'] = 'NOT_SAVE';    
                }
            } else {
                $data['s'] = 0;
                $data['r'] = 'NOT_TRANSACTION_REQUIREMENT_PER_USER';
            }   
        } else {
            $data['s'] = 0;
            $data['r'] = 'NOT_AVIABLE';
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
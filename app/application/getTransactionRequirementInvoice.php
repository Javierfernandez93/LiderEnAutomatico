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
            $data['transaction'] = format($TransactionRequirementPerUser->data(),$TransactionRequirementPerUser);
            $data['s'] = 1;
            // d($data);
            $data['r'] = 'DATA_OK';
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

function format(array $transaction_requirement_per_user = null,$TransactionRequirementPerUser = null) : array
{
    $transaction_requirement_per_user['catalog_payment_method'] = (new GranCapital\CatalogPaymentMethod)->get($TransactionRequirementPerUser->catalog_payment_method_id);
    
    $transaction_requirement_per_user['catalog_payment_method']['additional_data'] = json_decode($transaction_requirement_per_user['catalog_payment_method']['additional_data']);

    return $transaction_requirement_per_user;
}

echo json_encode($data);
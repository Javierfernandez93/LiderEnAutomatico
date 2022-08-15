<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    require_once TO_ROOT . 'system/lib/Stripe/init.php';

    \Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    $customer = \Stripe\Customer::create([ 
        'name' => $UserLogin->getFullName(),  
        'email' => $UserLogin->email 
    ]); 

    if($customer)
    {
        $UserStripe = new GranCapital\UserStripe;
        
        if($UserStripe->updateCustomer($UserLogin->company_id,$customer->id))
        {
            $data['s'] = 1;
            $data['r'] = 'SAVE_OK';    
        } else {
            $data['s'] = 0;
            $data['r'] = 'NOT_SAVE_CUSTOMER';    
        }
    } else {
        $data['s'] = 0;
        $data['r'] = 'NOT_CUSTOMER';    
    }
} else {
    $data['s'] = 0;
    $data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode($data);
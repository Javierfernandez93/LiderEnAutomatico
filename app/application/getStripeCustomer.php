<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	$UserStripe = new GranCapital\UserStripe;

    if(!$UserStripe->existCustomer($UserLogin->company_id)) {
		createCustomer($UserLogin);
	}
        
	if($customer = retreiveCustomer($UserLogin))
	{
		// d($customer);
        $data['customer_id'] = $customer->id;    
		$data['s'] = 1;
        $data['r'] = 'DATA_OK';    
    } else {
        $data['s'] = 0;
        $data['r'] = 'NOT_CUSTOMER';    
    }
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

function retreiveCustomer(GranCapital\UserLogin $UserLogin) 
{
	require_once TO_ROOT . 'vendor3/autoload.php';

    $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

	return $stripe->customers->retrieve(
		$UserLogin->getCustomerId(),
		[]
	); 
}

function createCustomer(GranCapital\UserLogin $UserLogin) : bool
{
	require_once TO_ROOT . 'vendor3/autoload.php';

    $stripe = new \Stripe\StripeClient(JFStudio\Stripe::SECRET_KEY_SANDBOX);

    $customer = $stripe->customers->create([
        'name' => $UserLogin->getFullName(),  
        'email' => $UserLogin->email 
    ]); 

	if($customer)
	{
		$UserStripe = new GranCapital\UserStripe;

		if($UserStripe->updateCustomer($UserLogin->company_id,$customer->id))
		{
			return true; 
		} 
	}

	return false;
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserSupport = new GranCapital\UserSupport;

if($UserSupport->_loaded === true)
{
    if($data['id'])
    {
        require_once TO_ROOT .'/vendor2/autoload.php';
        
        $CoinpaymentsAPI = new CoinpaymentsAPI(CoinPayments\Api::private_key, CoinPayments\Api::public_key, 'json');
        
        if($response = $CoinpaymentsAPI->GetWithdrawalInformation($data['id']))
        {
            $data['response'] = $response;
            $data["s"] = 1;
            $data["r"] = "DATA_OK";   
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_RESPONSE";   
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_ID";    
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
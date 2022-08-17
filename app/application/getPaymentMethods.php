<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($catalogPaymentMethods = (new GranCapital\CatalogPaymentMethod)->getAll())
    {
        $data["catalogPaymentMethods"] = filter($catalogPaymentMethods,$UserLogin->_data['user_address']['country_id']);
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_TRANSACTIONS_FOUND";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

function filter(array $catalogPaymentMethods = null,int $country_id = null) : array
{
    return array_filter($catalogPaymentMethods, function($catalogPaymentMethod) use($country_id) {
        $country_ids = json_decode($catalogPaymentMethod['country_ids']);

        if(in_array($country_id,$country_ids)) 
        {
            return true;
        }

        if(in_array(-1,$country_ids))
        {
            return true;
        }

        return false;
    });
}

echo json_encode($data);
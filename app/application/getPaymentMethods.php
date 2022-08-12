<?php

use Google\Cloud\Vision\V1\Word;

 define("TO_ROOT", "../../");

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
    $country_code = (new World\Country)->getCountryCode($country_id);

    return array_filter($catalogPaymentMethods, function($catalogPaymentMethod) use($country_code) {
        if($catalogPaymentMethod['catalog_currency_id'] == GranCapital\CatalogCurrency::FIAT)
        {   
            if($country_code == 'MXN')
            {
                return $country_code == $catalogPaymentMethod['currency'];
            } 
            
            return $catalogPaymentMethod['currency'] != 'MXN';
        } 

        return true;
    });
}

echo json_encode($data);
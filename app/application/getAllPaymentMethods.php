<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserSupport = new GranCapital\UserSupport;

if($UserSupport->_loaded === true)
{
    $CatalogPaymentMethod = new GranCapital\CatalogPaymentMethod;
    
    if($catalogPaymentMethods = $CatalogPaymentMethod->getAll("WHERE catalog_payment_method.status != '-1'"))
    {
        $data["catalogPaymentMethods"] = $catalogPaymentMethods;
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data['r'] = "NOT_CATALOG_PAYMENT_METHODS";
        $data['s'] = 0;
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
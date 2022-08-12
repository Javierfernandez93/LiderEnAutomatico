<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['catalog_payment_method_id'])
    {
        $CatalogPaymentMethod = new GranCapital\CatalogPaymentMethod;

        if($CatalogPaymentMethod->cargarDonde('catalog_payment_method_id = ?',$data['catalog_payment_method_id']))
        {
            $CatalogPaymentMethod->recomended = 0;

            if($CatalogPaymentMethod->save())
            {
                $data["s"] = 1;
                $data["r"] = "DATA_OK";
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_UPDATE";       
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_CATALOG_PAYMENT_METHOD";   
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_CATALOG_PAYMENT_METHOD_ID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
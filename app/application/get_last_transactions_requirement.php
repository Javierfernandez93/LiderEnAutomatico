<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($lastTransactions = $UserLogin->getLastTransactions())
    {
        $data["lastTransactions"] = format($lastTransactions);
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

function format(array $lastTransactions = null) : array {
    $CatalogPaymentMethod = new GranCapital\CatalogPaymentMethod;
    return array_map(function ($lastTransaction) use($CatalogPaymentMethod) {
        $lastTransaction['catalogPaymentMethod'] = $CatalogPaymentMethod->get($lastTransaction['catalog_payment_method_id']);
        $lastTransaction['checkout_data'] = json_decode($lastTransaction['checkout_data']);
        $lastTransaction['total'] = $lastTransaction['ammount'] + $lastTransaction['fee'];

        return $lastTransaction;
    },$lastTransactions);
}

echo json_encode($data);
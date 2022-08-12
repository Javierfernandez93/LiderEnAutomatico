<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($transactions = $UserLogin->getAllTransactions())
    {
        $data["transactions"] = format($transactions);
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

function format(array $transactions = null) : array {
    $CatalogPaymentMethod = new GranCapital\CatalogPaymentMethod;
    return array_map(function ($transaction) use($CatalogPaymentMethod) {
        $transaction['catalogPaymentMethod'] = $CatalogPaymentMethod->get($transaction['catalog_payment_method_id']);
        $transaction['checkout_data'] = json_decode($transaction['checkout_data']);
        $transaction['total'] = $transaction['ammount'] + $transaction['fee'];

        return $transaction;
    },$transactions);
}

echo json_encode($data);
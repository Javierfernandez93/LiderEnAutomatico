<?php

use GranCapital\CatalogPaymentMethod;

 define("TO_ROOT", "../../");

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
            $data["singleAmmount"] = $TransactionRequirementPerUser->ammount;
            $data["ammount"] = $TransactionRequirementPerUser->ammount+$TransactionRequirementPerUser->fee;
            $data["currency"] = strtolower((new GranCapital\CatalogPaymentMethod)->getCurrency($TransactionRequirementPerUser->catalog_payment_method_id));
            $data["s"] = 1;
            $data["r"] = "DATA_OK";
        } else {
            $data["s"] = 1;
            $data["r"] = "NOT_TRANSACTION_REQUIREMENT_PER_USER";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_TRANSACTION_REQUIREMENT_PER_USER_ID";
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
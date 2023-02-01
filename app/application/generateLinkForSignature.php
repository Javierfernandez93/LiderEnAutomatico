<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";
require_once TO_ROOT . "/vendor/autoload.php";

use setasign\Fpdi\Fpdi;

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if(GranCapital\UserAddress::make(array_merge(['user_login_id' => $UserLogin->company_id],[$data['address']]))) {
        $data["save_address"] = true;
    }
    
    if(GranCapital\UserData::make(array_merge(['user_login_id' => $UserLogin->company_id],[
        'id_number' => $data['id_number'],
    ]))) {
        $data["save_investor"] = true;
    }

    if($sign_code = GranCapital\UserAccount::generateSignCode($UserLogin->company_id))
    {
        $data["url"] = HCStudio\Connection::getMainPath()."/apps/sign/add?s=".$sign_code;
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_PID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
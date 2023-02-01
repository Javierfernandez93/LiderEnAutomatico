<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if(GranCapital\InvestorPerUser::make(array_merge(['user_login_id' => $UserLogin->company_id],$data['investor']))) {
        $data["save_investor"] = true;
    }
    
    if(GranCapital\UserAddress::make(array_merge(['user_login_id' => $UserLogin->company_id],["address" => $data['address']]))) {
        $data["save_address"] = true;
    }
    
    if(GranCapital\UserData::make(array_merge(['user_login_id' => $UserLogin->company_id],[
        'id_number' => $data['id_number'],
    ]))) {
        $data["save_investor"] = true;
    }

    $data["s"] = 1;
    $data["r"] = "DATA_OK";
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
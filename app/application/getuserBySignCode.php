<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

if($data['sign_code'])
{
    if(($user_login_id = (new GranCapital\UserAccount)->getIdBySignCode($data['sign_code'])))
    {
        $data['user_login_id'] = $user_login_id;
        $data['s'] = 1;
        $data['r'] = 'DATA_OK';
    } else {
        $data['s'] = 0;
        $data['r'] = 'NOT_USER_ID';
    }
} else {
    $data["s"] = 0;
    $data["r"] = "NOT_SIGN_CODE";
}

echo json_encode($data);
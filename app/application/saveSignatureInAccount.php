<?php define("TO_ROOT", "../../");

require_once TO_ROOT . "system/core.php"; 

$data = HCStudio\Util::getHeadersForWebService();

if($data['user_login_id'])
{
    if($data['signature'])
	{
        if(GranCapital\UserAccount::attachSignature($data['user_login_id'],$data['signature']))
        {
            $data['r'] = 'DATA_OK';
            $data['s'] = 1;
        } else {
            $data['r'] = 'NOT_SAVE';
            $data['s'] = 0;
        }
	} else {
		$data['r'] = 'NOT_SIGNATURE';
		$data['s'] = 0;
	}
} else {
	$data['r'] = 'NOT_USER_LOGIN_ID';
	$data['s'] = 0;
}

echo json_encode($data); 
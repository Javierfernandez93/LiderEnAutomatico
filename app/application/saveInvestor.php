<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($data['number'])
    {
        if($data['password'])
        {
            $InvestorPerUser = new GranCapital\InvestorPerUser;

            if(!$InvestorPerUser->cargarDonde("user_login_id = ? AND status = ?",[$UserLogin->company_id,1]))
            {
                $InvestorPerUser->user_login_id = $UserLogin->company_id;
                $InvestorPerUser->create_date = time();
            }
            
            $InvestorPerUser->number = $data['number'];
            $InvestorPerUser->password = $data['password'];

            if($InvestorPerUser->save())
            {
                $data["s"] = 1;
                $data["r"] = "SAVE_OK";
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_SAVE";
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_PASSWORD";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_NUMBER";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    $UserReferral = new GranCapital\UserReferral;

    if($levels = $UserReferral->_getNetwork(-1,$UserLogin->company_id))
    {
        $data['workingDays'] = GranCapital\ProfitPerUser::getWorkingDays(date("Y/m/d H:i:s"));
        $data['levels'] = $levels;
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_DATA";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "INVALID_CREDENTIALS";
}

echo json_encode($data);
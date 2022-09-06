<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    $UserReferral = new GranCapital\UserReferral;

    if($referrals = $UserReferral->getReferrals($UserLogin->company_id))
    {
        $data['referrals'] = formatData($referrals);
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


function formatData(array $referrals = null) : array 
{
    $UserPlan = new GranCapital\UserPlan;
    $actives = 0;

    foreach ($referrals as $referral) 
    {
        if($plan = $UserPlan->getPlan($referral['user_login_id']))
        {
            $actives += 1;
        }
    }

    return [
        'amount' => sizeof($referrals),
        'actives' => $actives
    ];
}

echo json_encode($data);
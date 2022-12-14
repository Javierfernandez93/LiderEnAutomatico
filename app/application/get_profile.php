<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($investor = $UserLogin->getInvestorInfo())
    {
        $investor = $investor;
    } else {
        $investor = ['number'=>null,'password' => null];
    }

    $data["user"] = [
        'company_id' => $UserLogin->company_id,
        'email' => $UserLogin->email,
        'phone' => $UserLogin->_data['user_contact']['phone'],
        'names' => $UserLogin->_data['user_data']['names'],
        'id_number' => $UserLogin->_data['user_data']['id_number'],
        'address' => $UserLogin->_data['user_address']['address'],
        'birthday' => $UserLogin->_data['user_data']['birthday'],
        'investor' => $investor,
        'last_login_date' => $UserLogin->last_login_date,
        'ip_user_address' => $UserLogin->ip_user_address,
        'plan' => $UserLogin->getPlan(),
        'has_card' => $UserLogin->hasCard(),
        'image' => $UserLogin->_data['user_account']['image'],
        'country_id' => $UserLogin->_data['user_address']['country_id'],
        'referral_notification' => $UserLogin->_data['user_account']['referral_notification'] ? true : false,
        'referral_email' => $UserLogin->_data['user_account']['referral_email'] ? true : false,
        'info_email' => $UserLogin->_data['user_account']['info_email'] ? true : false,
        'referral' => $UserLogin->getReferral(),
    ];

    $Country = new World\Country;

    if($data['include_witdraw_methods'])
    {
        $data['withdraw_methods'] = (new GranCapital\WithdrawMethodPerUser)->getAll($UserLogin->company_id);
    }

    if($data['include_countries'])
    {
        $data["countries"] = $Country->getAllByWeb();
    }
    $data["s"] = 1;
    $data["r"] = "LOGGED_OK";
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
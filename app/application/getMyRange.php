<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($catalogPlans = (new GranCapital\CatalogPlan)->getAll())
    {
        $data["catalog_plan_id"] = $UserLogin->getMyRange();
        $data["catalogPlans"] = format($catalogPlans);
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NPT_PLANS_FOUND";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

function format(array $catalogPlans = null) : array 
{
    return array_map(function($catalogPlan){
        $catalogPlan['additional_data'] = json_decode($catalogPlan['additional_data']);
        $catalogPlan['description_list'] = json_decode($catalogPlan['description_list']);
        return $catalogPlan;
    },$catalogPlans);
}

echo json_encode($data);
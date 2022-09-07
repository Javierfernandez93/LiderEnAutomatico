<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserSupport = new GranCapital\UserSupport;

if($UserSupport->_loaded === true)
{
    if($data['tool_id'])
    {
        $Tool = new GranCapital\Tool;
        
        if($Tool->cargarDonde("tool_id = ?",$data['tool_id']))
        {
            $Tool->status = GranCapital\Tool::DELETED;
            
            if($Tool->save())
            {
                $data["s"] = 1;
                $data["r"] = "DATA_OK";
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_UPDATE";
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_NOTICE";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_TOOL_ID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
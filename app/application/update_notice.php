<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserSupport = new GranCapital\UserSupport;

if($UserSupport->_loaded === true)
{
    if($data['notice_id'])
    {
        $Notice = new GranCapital\Notice;

        if($Notice->cargarDonde("notice_id= ?",$data['notice_id']))
        {
            $Notice->title = $data['title'];
            $Notice->description = $data['description'];
            $Notice->start_date = $data['start_date'] ? strtotime($data['start_date']) : $Notice->start_date;
            $Notice->end_date = $data['end_date'] ? strtotime($data['end_date']) : $Notice->end_date;
            $Notice->catalog_notice_id = $data['catalog_notice_id'];
            $Notice->catalog_priority_id = $data['catalog_priority_id'];

            if($Notice->save())
            {
                $data["s"] = 1;
                $data["r"] = "DATA_OK";
            } else {
                $data["s"] = 0;
                $data["r"] = "NOT_SAVE";
            }
        } else {
            $data["s"] = 0;
            $data["r"] = "NOT_NOTICE";
        }
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_NOTICE_ID";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
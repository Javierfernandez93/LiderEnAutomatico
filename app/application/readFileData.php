<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";
require_once TO_ROOT . "/vendor/autoload.php";

$data = HCStudio\Util::getHeadersForWebService();

$UserSupport = new GranCapital\UserSupport;

if($UserSupport->_loaded === true)
{    
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($data['file']);
    $reader->setReadDataOnly(TRUE);
    
    $spreadsheet = $reader->load($data['file']);
    $spreadsheet = $spreadsheet->getActiveSheet();
    $data_array =  $spreadsheet->toArray();
 
    if($users = GranCapital\UserLogin::sanitizeUserDataForImport($data_array))
    {
        $data["users"] = $users;
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_USERS";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

echo json_encode($data);
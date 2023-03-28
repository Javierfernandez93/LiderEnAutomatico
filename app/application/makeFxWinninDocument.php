<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";
require_once TO_ROOT . "/vendor/autoload.php";

use GranCapital\UserAccount;
use setasign\Fpdi\Fpdi;

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
    if($path = createFXWinningDocument($data,$UserLogin->company_id))
    {
        if(GranCapital\InvestorPerUser::make(array_merge(['user_login_id' => $UserLogin->company_id],$data['investor']))) {
            $data["save_investor"] = true;
        }
        
        if(GranCapital\UserAddress::make(array_merge(['user_login_id' => $UserLogin->company_id],[$data['address']]))) {
            $data["save_address"] = true;
        }
        
        if(GranCapital\UserData::make(array_merge(['user_login_id' => $UserLogin->company_id],[
            'id_number' => $data['id_number'],
        ]))) {
            $data["save_investor"] = true;
        }

        $data["path"] = $path;
        $data["s"] = 1;
        $data["r"] = "DATA_OK";
    } else {
        $data["s"] = 0;
        $data["r"] = "NOT_NUMBER";
    }
} else {
	$data["s"] = 0;
	$data["r"] = "NOT_FIELD_SESSION_DATA";
}

function importPage($pdf = null,int $page)
{
    $pdf->AddPage(); 
    $tplIdx = $pdf->importPage($page); 
    $pdf->useTemplate($tplIdx);
}

function createFXWinningDocument(array $data = null,int $user_login_id = null)
{
    $pdf = new FPDI();

    $pdf->AddPage(); 

    $lpoa = (new GranCapital\UserAccount)->getLPOA($user_login_id);
    
    $pdf->setSourceFile(GranCapital\FXWinning::getSourceTemplate(TO_ROOT,$lpoa)); 
    $tplIdx = $pdf->importPage(1); 
    $pdf->useTemplate($tplIdx); 

    $pdf->SetFont('Arial', '', '12'); 
    $pdf->SetTextColor(0,0,0);

    $nameCords = GranCapital\FXWinning::getCoords('name');
    $pdf->SetXY($nameCords['x'], $nameCords['y']);
    $pdf->Write(0, $data['names']);
    
    $fullNameCords = GranCapital\FXWinning::getCoords('fullName');
    $pdf->SetXY($fullNameCords['x'], $fullNameCords['y']);
    $pdf->Write(0, $data['names']);
    
    $idNumberCords = GranCapital\FXWinning::getCoords('idNumber');
    $pdf->SetXY($idNumberCords['x'], $idNumberCords['y']);
    $pdf->Write(0, $data['id_number']);

    $addressCords = GranCapital\FXWinning::getCoords('address');
    $pdf->SetXY($addressCords['x'], $addressCords['y']);
    $pdf->Write(0, $data['address']);
    
    $emailCords = GranCapital\FXWinning::getCoords('email');
    $pdf->SetXY($emailCords['x'], $emailCords['y']);
    $pdf->Write(0, $data['email']);
    
    importPage($pdf,2);
    importPage($pdf,3);
    importPage($pdf,4);
    importPage($pdf,5);

    $investorNumberCords = GranCapital\FXWinning::getCoords('investorNumber');
    $pdf->SetXY($investorNumberCords['x'], $investorNumberCords['y']);
    $pdf->Write(0, $data['investor']['number']);

    importPage($pdf,6);
    importPage($pdf,7);
    importPage($pdf,8);
    importPage($pdf,9);
    importPage($pdf,10);

    if($lpoa != 2)
    {
        $fullNameEndCords = GranCapital\FXWinning::getCoords('fullNameEnd');
        $pdf->SetXY($fullNameEndCords['x'], $fullNameEndCords['y']);
        $pdf->Write(0, $data['names']);
        
        $birthdayCords = GranCapital\FXWinning::getCoords('birthday');
        $pdf->SetXY($birthdayCords['x'], $birthdayCords['y']);
        $pdf->Write(0, date("Y-m-d"));
    
        $signatureCords = GranCapital\FXWinning::getCoords('signature');
        $pdf->Image($data['signature'], $signatureCords['x'], $signatureCords['y'], 40);
    } else {
        $fullNameEndCords = GranCapital\FXWinning::getCoords('fullNameEndSpecial');
        $pdf->SetXY($fullNameEndCords['x'], $fullNameEndCords['y']);
        $pdf->Write(0, $data['names']);
        
        $birthdayCords = GranCapital\FXWinning::getCoords('birthdaySpecial');
        $pdf->SetXY($birthdayCords['x'], $birthdayCords['y']);
        $pdf->Write(0, date("Y-m-d"));
    
        $signatureCords = GranCapital\FXWinning::getCoords('signatureSpecial');
        $pdf->Image($data['signature'], $signatureCords['x'], $signatureCords['y'], 40);
    }

    // d(1);
    $path = GranCapital\FXWinning::getSourceTemplateOutput(TO_ROOT,$user_login_id);

    $pdf->Output(GranCapital\FXWinning::getSourceTemplateOutput(TO_ROOT,$user_login_id), 'F');
    
    return $path;
}


echo json_encode($data);
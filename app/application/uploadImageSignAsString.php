<?php define("TO_ROOT", "../../");

require_once TO_ROOT . "system/core.php"; 

$data = HCStudio\Util::getHeadersForWebService();

if(true)
{
    if($data['image'])
	{
        $target_path = TO_ROOT.'src/files/img/sign/' .time().'.png';
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image']));

        if(file_put_contents($target_path, $image))
        {
            $data['target_path'] = $target_path;
            $data['r'] = 'DATA_OK';
            $data['s'] = 1;
        } else {
            $data['r'] = 'NOT_SAVE';
            $data['s'] = 0;
        }
	} else {
		$data['r'] = 'NOT_IMAGE';
		$data['s'] = 0;
	}
} else {
	$data['r'] = 'INVALID_CREDENTIALS';
	$data['s'] = 0;
}

echo json_encode($data); 
<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	$data['paymentIntervals'] = [
		0 => [
			'name' => 'month',
			'text' => 'Mes'
		],
		1 => [
			'name' => 'year',
			'text' => 'Año'
		],
		2 => [
			'name' => 'week',
			'text' => 'Semana'
		],
		3 => [
			'name' => 'day',
			'text' => 'Día'
		]
	];
	$data['s'] = 1;
	$data['r'] = 'DATA_OK';
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
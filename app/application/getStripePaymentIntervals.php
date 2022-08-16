<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	$data['paymentIntervals'] = [
		0 => [
			'interval' => 'month',
			'text' => 'Mensual'
		],
		1 => [
			'interval' => 'year',
			'text' => 'Anual'
		],
		2 => [
			'interval' => 'week',
			'text' => 'Semanal'
		],
		3 => [
			'interval' => 'day',
			'text' => 'Diario'
		]
	];
	$data['s'] = 1;
	$data['r'] = 'DATA_OK';
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
<?php define('TO_ROOT', '../../');

require_once TO_ROOT . 'system/core.php';

$data = HCStudio\Util::getHeadersForWebService();

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === true)
{
	if($data['transaction_requirement_per_user_id'])
	{
		$TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;

		if($TransactionRequirementPerUser->cargarDonde('transaction_requirement_per_user_id = ? AND status = ?',[$data['transaction_requirement_per_user_id'],GranCapital\TransactionRequirementPerUser::PENDING]))
		{
			require_once TO_ROOT . 'system/lib/Stripe/init.php';

			\Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

			$data['ammount'] = $TransactionRequirementPerUser->ammount + $TransactionRequirementPerUser->fee;

			$intent = \Stripe\PaymentIntent::create([
				'amount' => JFStudio\Stripe::formatAmmount($data['ammount']),
			    'currency' => strtolower((new GranCapital\CatalogPaymentMethod)->getCurrency($TransactionRequirementPerUser->catalog_payment_method_id)),
			    'payment_method_types' => ['card'],
			    'description' => 'GranCapital-'.$TransactionRequirementPerUser->getId()
			]);

			$TransactionRequirementPerUser->txn_id = $intent->id;

			if($TransactionRequirementPerUser->save())
			{
				$data['client_secret'] = $intent->client_secret;
				$data['public_key'] = JFStudio\Stripe::PULIC_KEY_SANDBOX;
				$data['s'] = 1;
				$data['r'] = 'DATA_OK';
			} else {
				$data['s'] = 0;
				$data['r'] = 'NOT_SAVE';
			}
		} else {
			$data['s'] = 0;
			$data['r'] = 'NOT_TRANSACTION_REQUIREMENT_PER_USER';
		}
	} else {
		$data['s'] = 0;
		$data['r'] = 'NOT_TRANSACTION_REQUIREMENT_PER_USER_ID';
	}
} else {
	$data['s'] = 0;
	$data['r'] = 'INVALID_CREDENTIALS';
}

echo json_encode(HCStudio\Util::compressDataForPhone($data)); 
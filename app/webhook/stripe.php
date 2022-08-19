<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

require TO_ROOT . 'vendor3/autoload.php';

\Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_RAPLcBgSZlAdx3vZrfbHgbajmVtaHTyV';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

saveEvent($event,$event->type);

// Handle the event
switch ($event->type) {
  case 'invoice.payment_succeeded':
    addFundsBySuscription($event->data->object->customer,$event->data->object->payment_intent,JFStudio\Stripe::unformatAmmount($event->data->object->amount_due),$event->data->object->currency,$event->data->object->invoice_pdf,$event->data->object->id);
    break;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
    break;
  default:
    echo 'Received unknown event type ' . $event->type;
}

function saveEvent($event,$type)
{
    $EventStripe = new GranCapital\EventStripe;
    $EventStripe->type = $type;
    $EventStripe->create_date = time();
    $EventStripe->data = json_encode($event);
    $EventStripe->save();
}

function addFundsBySuscription(string $customer_id,string $txn_id,float $ammount,string $currency,string $image = null,string $payment_reference = null) : bool
{
    if($catalog_payment_method = (new GranCapital\CatalogPaymentMethod)->getStripePaymentMethodByCurrency(strtoupper($currency)))
    {
        $TransactionRequirementPerUser = new GranCapital\TransactionRequirementPerUser;
        $TransactionRequirementPerUser->user_login_id = (new GranCapital\UserStripe)->getUserLoginId($customer_id);
        $TransactionRequirementPerUser->catalog_payment_method_id = $catalog_payment_method['catalog_payment_method_id'];
        $TransactionRequirementPerUser->txn_id = $txn_id;
        $TransactionRequirementPerUser->user_support_id = 0;
        $TransactionRequirementPerUser->fee = ($catalog_payment_method['fee'] * $ammount) / (100+$catalog_payment_method['fee']);
        $TransactionRequirementPerUser->ammount = $ammount - $TransactionRequirementPerUser->fee;
        $TransactionRequirementPerUser->image = $image;
        $TransactionRequirementPerUser->payment_reference = $payment_reference;
        $TransactionRequirementPerUser->registration_date = time();
        $TransactionRequirementPerUser->create_date = time();

        if($TransactionRequirementPerUser->save())
        {
            $TransactionRequirementPerUser->checkout_data = json_encode([
                'link' => "../../apps/wallet/invoice?trpid={$TransactionRequirementPerUser->getId()}"
            ]);

            if($TransactionRequirementPerUser->save())
            {
                $Curl = new JFStudio\Curl;
                $Curl->get(HCStudio\Connection::getMainPath()."/app/application/apply_deposit.php",[
                    'user' => HCStudio\Util::$username,
                    'password' => HCStudio\Util::$password,
                    'validation_method' => GranCapital\TransactionRequirementPerUser::STRIPE_CDN,
                    'transaction_requirement_per_user_id' => $TransactionRequirementPerUser->getId()
                ]);

                echo HCStudio\Connection::getMainPath()."/app/application/apply_deposit.php".http_build_query([
                    'user' => HCStudio\Util::$username,
                    'password' => HCStudio\Util::$password,
                    'validation_method' => GranCapital\TransactionRequirementPerUser::STRIPE_CDN,
                    'transaction_requirement_per_user_id' => $TransactionRequirementPerUser->getId()
                ]);

                if($response = $Curl->getResponse(true))
                {
                    $data['response'] = $response;

                    if($response['s'] == 1)
                    {
                        return true;
                    } 
                } 
            }
        }
    }

    return false;
}

http_response_code(200);
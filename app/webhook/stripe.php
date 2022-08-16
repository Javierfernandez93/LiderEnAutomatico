<?php define("TO_ROOT", "../../");

require_once TO_ROOT. "/system/core.php";

require TO_ROOT . 'vendor3/autoload.php';

\Stripe\Stripe::setApiKey(JFStudio\Stripe::SECRET_KEY_SANDBOX);

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_21a4025a16c14bd3b3dc19c050259d9742d6f765c64a2ae317da1ee3c2f30955';

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
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

function saveEvent($event,$type)
{
    require_once TO_ROOT. "system/core.php";

    $EventStripe = new GranCapital\EventStripe;
    $EventStripe->type = $type;
    $EventStripe->create_date = time();
    $EventStripe->data = json_encode($event);
    $EventStripe->save();
}

http_response_code(200);
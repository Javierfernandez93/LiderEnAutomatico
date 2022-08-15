<?php define("TO_ROOT", "../../");
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require TO_ROOT . 'vendor3/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51GM2sSHwPw0UyNibVSfLaOZ76TPbCh8msfj1T98I0bTmX3eVpIzekIEImG44dHUgcYRQpDW6FWcDkLipHXRfJPPE00IcfzSuyd');

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
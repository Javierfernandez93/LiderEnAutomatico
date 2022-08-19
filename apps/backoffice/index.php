<?php define("TO_ROOT", "../..");

require_once TO_ROOT . "/system/core.php";

$UserLogin = new GranCapital\UserLogin;

if($UserLogin->_loaded === false) {
	HCStudio\Util::redirectTo(TO_ROOT."/apps/login/");
}

$UserLogin->checkRedirection();

$Layout = JFStudio\Layout::getInstance();

$route = JFStudio\Router::Backoffice;
$Layout->init(JFStudio\Router::getName($route),'index',"backoffice",'',TO_ROOT.'/');

$Layout->setScriptPath(TO_ROOT . '/src/');
$Layout->setScript([
	'backoffice.vue.js'
]);

$event = json_decode('{"id":"evt_1LYJugHwPw0UyNib3EkA42EJ","object":"event","api_version":"2020-03-02","created":1660872138,"data":{"object":{"id":"in_1LYIkWHwPw0UyNibmu9OtofG","object":"invoice","account_country":"MX","account_name":"gran capital ","account_tax_ids":null,"amount_due":117600,"amount_paid":117600,"amount_remaining":0,"application":null,"application_fee_amount":null,"attempt_count":1,"attempted":true,"auto_advance":false,"automatic_tax":{"enabled":false,"status":null},"billing_reason":"subscription_cycle","charge":"ch_3LYJudHwPw0UyNib0cRukgIc","collection_method":"charge_automatically","created":1660867664,"currency":"mxn","custom_fields":null,"customer":"cus_MFk5qa3jh0sS8c","customer_address":null,"customer_email":"javier.fernandez.pa93@gmail.com","customer_name":"javier","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discount":null,"discounts":[],"due_date":null,"ending_balance":0,"footer":null,"hosted_invoice_url":"https:\/\/invoice.stripe.com\/i\/acct_1GM2sSHwPw0UyNib\/test_YWNjdF8xR00yc1NId1B3MFV5TmliLF9NR3FSMjhVQTJWNXNSRUtOQ3JkVjBMSFRUOHNVTVhhLDUxNDEyOTM40200PVBteGOI?s=ap","invoice_pdf":"https:\/\/pay.stripe.com\/invoice\/acct_1GM2sSHwPw0UyNib\/test_YWNjdF8xR00yc1NId1B3MFV5TmliLF9NR3FSMjhVQTJWNXNSRUtOQ3JkVjBMSFRUOHNVTVhhLDUxNDEyOTM40200PVBteGOI\/pdf?s=ap","last_finalization_error":null,"lines":{"object":"list","data":[{"id":"il_1LYIkWHwPw0UyNibrWPGqEHZ","object":"line_item","amount":117600,"amount_excluding_tax":117600,"currency":"mxn","description":"1 \u00d7 Plan recurrente (at $1,176.00 \/ day)","discount_amounts":[],"discountable":true,"discounts":[],"livemode":false,"metadata":[],"period":{"end":1660954045,"start":1660867645},"plan":{"id":"price_1LXZn7HwPw0UyNibHFQgFfJ1","object":"plan","active":false,"aggregate_usage":null,"amount":117600,"amount_decimal":"117600","billing_scheme":"per_unit","created":1660694845,"currency":"mxn","interval":"day","interval_count":1,"livemode":false,"metadata":[],"nickname":null,"product":"prod_MFlbiK46qhHG7g","tiers":null,"tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"price":{"id":"price_1LXZn7HwPw0UyNibHFQgFfJ1","object":"price","active":false,"billing_scheme":"per_unit","created":1660694845,"currency":"mxn","custom_unit_amount":null,"livemode":false,"lookup_key":null,"metadata":[],"nickname":null,"product":"prod_MFlbiK46qhHG7g","recurring":{"aggregate_usage":null,"interval":"day","interval_count":1,"trial_period_days":null,"usage_type":"licensed"},"tax_behavior":"unspecified","tiers_mode":null,"transform_quantity":null,"type":"recurring","unit_amount":117600,"unit_amount_decimal":"117600"},"proration":false,"proration_details":{"credited_items":null},"quantity":1,"subscription":"sub_1LXZn7HwPw0UyNib9eWt4yB9","subscription_item":"si_MG5z3egQEXc2Z1","tax_amounts":[],"tax_rates":[],"type":"subscription","unit_amount_excluding_tax":"117600"}],"has_more":false,"total_count":1,"url":"\/v1\/invoices\/in_1LYIkWHwPw0UyNibmu9OtofG\/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"799883BB-0016","on_behalf_of":null,"paid":true,"paid_out_of_band":false,"payment_intent":"pi_3LYJudHwPw0UyNib03TgllKi","payment_settings":{"default_mandate":null,"payment_method_options":null,"payment_method_types":null},"period_end":1660867645,"period_start":1660781245,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"quote":null,"receipt_number":null,"rendering_options":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1660872134,"marked_uncollectible_at":null,"paid_at":1660872134,"voided_at":null},"subscription":"sub_1LXZn7HwPw0UyNib9eWt4yB9","subtotal":117600,"subtotal_excluding_tax":117600,"tax":null,"tax_percent":null,"test_clock":null,"total":117600,"total_discount_amounts":[],"total_excluding_tax":117600,"total_tax_amounts":[],"transfer_data":null,"webhooks_delivered_at":1660867665}},"livemode":false,"pending_webhooks":1,"request":{"id":null,"idempotency_key":null},"type":"invoice.payment_succeeded"}');

addFundsBySuscription($event->data->object->customer,$event->data->object->payment_intent,JFStudio\Stripe::unformatAmmount($event->data->object->amount_due),$event->data->object->currency,$event->data->object->invoice_pdf,$event->data->object->id);

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

$Layout->setVar([
	'route' =>  $route,
	'UserLogin' => $UserLogin
]);
$Layout();
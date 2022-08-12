$(document).ready(function(){
  let stripeInt = new StripeInt;
  var stripe = Stripe(stripeInt.getPublicKey());
  var elements = stripe.elements();

  stripeInt.init((response)=>{
    if(response.s == 1)
    {
      stripeInt.setClientSecret(response.client_secret);
    }
  },{buy_per_user_login_id:getParam("bpulid")});

  var style = {
    base: {
      color: "#32325d",
    }
  };

  var card = elements.create("card", { style: style });
  card.mount("#card-element");
  card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      showError(event.error.message);
    } else {
      $("#submit").removeAttr("disabled");
      $("#card-errors").addClass("d-none").text("");
    }
  });

  var submitButton = document.getElementById('submit');

  submitButton.addEventListener('click', function(ev) {
    $("#submit").text("Espere...").attr("disabled",true);

    stripe.confirmCardPayment(stripeInt.getClientSecret(), {
      payment_method: {card: card}
    }).then(function(result) {
      console.log(result);
      if (result.error) {
        console.log(result.error);
          $("#submit").text("Pagar ahora").removeAttr("disabled");
          showError(result.error.message);  
      } else {
        if(result.paymentIntent.status === 'succeeded') {
          stripeInt.checkStripePayment((response)=>{
            if(response.s == 1)
            {
              runAnimation();
            }
          },{id:result.paymentIntent.id});
        }
      }
    });
  });


  function showError(message)
  {
    $("#submit").attr("disabled",true);
      $("#card-errors").removeClass("d-none").text(message);
  }

  function runAnimation()
  {
    setTimeout(()=>{
      $("#box-stripe").addClass("d-none-hide");
      $("#stripe").addClass("d-none-hide");
      $("#box-stripe-success").removeClass("d-none");
      $("#bm").removeClass("d-none");
      runAnimation();
    },1000);

    var animation = bodymovin.loadAnimation({
      container: document.getElementById('bm'),
      renderer: 'svg',
      loop: false,
      autoplay: true,
      path: '../../src/files/json/done.json'
    });
  }
});

class StripeInt extends Http{
  constructor() {
    super();
    this.client_secret = null;
    this.public_key = "pk_test_Emr0QcdYN1t5FdOEHKQMPhos007kikkSMO";
    // this.public_key = "pk_test_LERB57zSWRQ37sOehm4ADL14";
  }
  getPublicKey() {
    return this.public_key;
  }
  getClientSecret() {
    return this.client_secret;
  }
  setClientSecret(client_secret) {
    this.client_secret = client_secret;
  }
  checkStripePayment(callback,data){
    return this.call('./../../app/application/check_stripe_payment.php',data,callback,false);
  }
  init(callback,data){
    return this.call('./../../app/application/get_stripe_client_secret.php',data,callback,false);
  }
}
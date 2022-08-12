import { User } from '../../src/js/user.module.js?t=5'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            loadingButton: false,
            loading: null,
            client_secret: null,
            public_key: null,
            elements: null,
            ammount: null,
            Stripe: null,
            card: null,
            error: null,
            paymentStatus: null,
            STATUS: {
                PAYMENT_PENDING : 0,
                PAYMENT_DONE : 1,
                PAYMENT_EXPIRED : 2,
                PAYMENT_CANCELED : 3
            },
        }
    },
    watch: {
    },
    methods: {
        initStripe: function () {
            this.loading = true

            this.Stripe = Stripe(this.public_key);
            this.elements = this.Stripe.elements();
            this.card = this.elements.create("card", 
                { 
                    style: {
                        base: {
                            color: "#32325d",
                        }
                    } 
                }
            );

            this.card.mount("#card-element");
            
            this.card.addEventListener('change', (event) => {
                this.error = event.error != undefined ? event.error : false
            });

            this.loading = false
        },
        showError: function(message) {
            $("#submit").attr("disabled", true);
            $("#card-errors").removeClass("d-none").text(message);
        },
        makePayment: function()
        {
            this.loadingButton = true

            this.Stripe.confirmCardPayment(this.client_secret, {
                payment_method: { card: this.card }
            }).then((result) => {
                this.loadingButton = false

                if (result.error) {
                    this.error = result.error
                } else {
                    if (result.paymentIntent.status === 'succeeded') 
                    {
                        this.User.checkStripePayment({id: result.paymentIntent.id},(response) => {

                            console.log(response)
                            if (response.s == 1) {
                                this.paymentStatus = this.STATUS.PAYMENT_DONE
                            }
                            console.log(this.paymentStatus)
                        });
                    }
                }
            });
        },
        getStripeClientSecret: function (transaction_requirement_per_user_id) {
            return new Promise((resolve,reject) => {
                this.loading = true

                this.User.getStripeClientSecret({ transaction_requirement_per_user_id: transaction_requirement_per_user_id }, (response) => {
                    this.loading = false

                    if (response.s == 1) {
                        resolve()

                        this.client_secret = response.client_secret
                        this.public_key = response.public_key
                        this.ammount = response.ammount
                        
                        this.paymentStatus = this.STATUS.PAYMENT_PENDING
                    } else if(response.r == 'NOT_TRANSACTION_REQUIREMENT_PER_USER') {
                        this.paymentStatus = this.STATUS.PAYMENT_EXPIRED

                        reject()
                    }

                    
                })
            })
        },
    },
    mounted() {
        if(getParam('trpid'))
        {
            this.getStripeClientSecret(getParam('trpid')).then(() => {
                this.initStripe()
            }).catch((res)=>{
                console.log(1)
            })
        }
    },
}).mount('#app')
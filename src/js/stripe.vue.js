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
            currency: null,
            Stripe: null,
            card: null,
            customer : {
                customer_id: null
            },
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
            this.loadingButton = false
        },
        showError: function(message) {
            $("#submit").attr("disabled", true);
            $("#card-errors").removeClass("d-none").text(message);
        },
        makePayment: function()
        {
            console.log(1)
            this.loadingButton = true

            this.User.createStripePayment({customer_id:this.customer.customer_id,ammount:this.ammount,currency:this.currency,transaction_requirement_per_user_id:getParam('trpid')},(response)=>{
                this.client_secret = response.client_secret

                this.Stripe.confirmCardPayment(this.client_secret, {
                    payment_method: { card: this.card }
                }).then((result) => {
                    this.loadingButton = false

                    if (result.error) {
                        this.error = result.error
                    } else {
                        console.log("result",result)
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
                })
            })
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
                        // this.ammount = response.ammount
                    } else if(response.r == 'NOT_TRANSACTION_REQUIREMENT_PER_USER') {
                        this.paymentStatus = this.STATUS.PAYMENT_EXPIRED

                        reject()
                    }

                    
                })
            })
        },
        getStripePublicKey: function () {
            return new Promise((resolve,reject) => {
                this.loading = true

                this.User.getStripePublicKey({}, (response) => {
                    this.loading = false

                    if (response.s == 1) {
                        resolve(response.public_key)
                    } else {
                        reject()
                    }
                })
            })
        },
        getTransactionAmmount: function (transaction_requirement_per_user_id) {
            return new Promise((resolve,reject) => {
                this.loading = true

                this.User.getTransactionAmmount({transaction_requirement_per_user_id:transaction_requirement_per_user_id}, (response) => {
                    this.loading = false

                    if (response.s == 1) {
                        resolve(response)
                    } else {
                        reject()
                    }
                })
            })
        },
        getStripeCustomer: function () {
            return new Promise((resolve,reject) => {
                this.loading = true

                this.User.getStripeCustomer({}, (response) => {
                    this.loading = false

                    if (response.s == 1) {
                        resolve(response.customer_id)
                    } else {
                        reject()
                    }
                })
            })
        },
    },
    mounted() {
        if(getParam('trpid'))
        {
            this.loadingButton = true

            this.getTransactionAmmount(getParam('trpid')).then((response) => {
                
                this.ammount = response.ammount
                this.currency = response.currency

                this.paymentStatus = this.STATUS.PAYMENT_PENDING

                this.getStripeCustomer().then((customer_id) => {
                    this.getStripePublicKey().then((public_key) => {
                        this.public_key = public_key

                        this.initStripe()
                    }).catch((error)=>{
                        console.log('error',error)
                    })
                }).catch((error)=>{
                    console.log('error',error)
                })
            })
        }
    },
}).mount('#app')
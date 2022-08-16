import { User } from '../../src/js/user.module.js?t=5'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            loadingButton: false,
            loading: null,
            recurring : {
                interval : 'month'
            },
            customer : {
                customer_id: null
            },
            paymentIntervals: null,
            client_secret: null,
            public_key: null,
            elements: null,
            currency: null,
            ammount: null,
            singleAmmount: null,
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

            this.Stripe = Stripe(this.public_key)
            this.elements = this.Stripe.elements()
            this.card = this.elements.create("card", 
                { 
                    style: {
                        base: {
                            color: "#32325d",
                        }
                    } 
                }
            )

            this.card.mount("#card-element")
            
            this.card.addEventListener('change', (event) => {
                this.error = event.error != undefined ? event.error : false
            })

            this.loading = false
        },
        showError: function(message) {
            $("#submit").attr("disabled", true);
            $("#card-errors").removeClass("d-none").text(message);
        },
        getPaymentIntervalText: function()
        {
            let text = null
            this.paymentIntervals.map((paymentInterval)=>{
                if(paymentInterval.interval == this.recurring.interval)
                {
                    text = paymentInterval.text
                }
            })

            return text
        },
        makePayment: function()
        {
            let alert = alertCtrl.create({
                title: "Aviso",
                subTitle: `Se creará una suscripción ${this.getPaymentIntervalText()} por $ ${this.singleAmmount.numberFormat(2)} ${this.currency}`,
                buttons: [
                    {
                        text: "Sí, realizar pago",
                        role: "cancel",
                        class: 'btn-success',
                        handler: (data) => {
                            this.loadingButton = true
            
                            this.User.createStripeSuscription({customer_id:this.customer.customer_id,interval:this.recurring.interval,ammount:this.ammount,currency:this.currency,transaction_requirement_per_user_id:getParam('trpid')},(response)=>{
                                this.client_secret = response.client_secret

                                if(response.s == 1)
                                {
                                    this.Stripe.confirmCardPayment(this.client_secret, {
                                        payment_method: { 
                                            card: this.card 
                                        }
                                    }).then((result) => {
                                        this.loadingButton = false

                                        if (result.error) {
                                            this.error = result.error
                                        } else {
                                            if (result.paymentIntent.status === 'succeeded') 
                                            {
                                                // subscriptionId
                                                // subscr_plan_id
                                                // result.paymentIntent
                                                // paymentIntent: result.paymentIntent
                                                
                                                this.User.checkStripeSuscription({id: result.paymentIntent.id},(response) => {
                                                    if (response.s == 1) {
                                                        this.paymentStatus = this.STATUS.PAYMENT_DONE
                                                    }
                                                })
                                            }
                                        }
                                    })
                                }
                            })
                        },
                    },
                    {
                        text: "Cancelar",
                        role: "cancel",
                        handler: (data) => {
                        },
                    },
                ],
            })

            alertCtrl.present(alert.modal); 

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
        getStripePaymentIntervals: function () {
            return new Promise((resolve,reject) => {
                this.loading = true

                this.User.getStripePaymentIntervals({}, (response) => {
                    this.loading = false

                    if (response.s == 1) {
                        resolve(response.paymentIntervals)
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
    },
    mounted() {
        if(getParam('trpid'))
        {
            this.getTransactionAmmount(getParam('trpid')).then((response) => {
                this.paymentStatus = this.STATUS.PAYMENT_PENDING

                this.singleAmmount = response.singleAmmount
                this.ammount = response.ammount
                this.currency = response.currency

                this.getStripePublicKey().then((public_key) => {
                    this.public_key = public_key

                    this.getStripeCustomer().then((customer_id) => {
                        this.customer.customer_id = customer_id

                        this.getStripePaymentIntervals().then((paymentIntervals) => {
                            this.paymentIntervals = paymentIntervals
                            this.recurring.interval = paymentIntervals[0].interval

                            this.getPaymentIntervalText()

                            this.initStripe()
                        }).catch((error)=>{
                            console.log('error',error)
                        })
                    }).catch((error)=>{
                        console.log('error',error)
                    })
                }).catch((error)=>{
                    console.log('error',error)
                })
            }).catch((error)=>{
                console.log('error',error)
            })
        }
    },
}).mount('#app')
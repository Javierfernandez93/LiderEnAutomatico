import { User } from '../../src/js/user.module.js?t=5.1.4'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            feedback: null,
            CatalogCurrency : {
                PAYPAL: 5,
                STRIPE: 6,
                STRIPE_USA: 7,
                TRANSFER_MXN: 8,
                TRANSFER_COP: 9,
                TRANSFER_USD: 10,
            },
            transaction: {
                catalog_payment_method: {
                    catalog_payment_method_id: null,
                },
                ammount: null,
                checkoutData: false
            },
            lastTransactions: [],
            catalogPaymentMethods: [],
            loading : false,
        }
    },
    watch: {
        withdraw: {
            handler() {
                this.withdrawComplete = this.withdraw.catalog_withdraw_method_id != null && (this.withdraw.ammount > 0 && this.withdraw.ammount <= this.balance)
            },
            deep: true,
        },
        user: {
            handler() {

            },
            deep: true
        },
    },
    methods: {
        setPaymentMethod: function (catalog_payment_method) {
            this.transaction.catalog_payment_method = catalog_payment_method
        },
        createTransactionRequirement: function () {
            this.loading = true
            this.User.createTransactionRequirement(this.transaction, (response) => {
                this.loading = false
                if (response.s == 1) {
                    this.transaction.checkoutData = response.checkoutData
                    this.getLastTransactionsRequirement()
                }
            })
        },
        viewAllDeposits: function () {
            window.location.href = '../../apps/wallet/allFunds'
        },
        getLastTransactionsRequirement: function () {
            this.User.getLastTransactionsRequirement({}, (response) => {
                if (response.s == 1) {
                    this.lastTransactions = response.lastTransactions
                }
            })
        },
        getPaymentMethods: function () {
            return new Promise((resolve) => {
                this.User.getPaymentMethods({}, (response) => {
                    if (response.s == 1) {
                        this.catalogPaymentMethods = response.catalogPaymentMethods
                    }

                    resolve()
                })
            })
        },
    },
    mounted() {
        this.getPaymentMethods().then(() => {
            this.getLastTransactionsRequirement()
        })
    },
}).mount('#app')
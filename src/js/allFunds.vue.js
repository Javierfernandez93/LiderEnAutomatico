import { User } from '../../src/js/user.module.js?t=4.1.1'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            query: null,
            transactionsAux: [],
            transactions: [],
            catalogPaymentMethods: [],
        }
    },
    watch: {
        query: {
            handler() {
                this.filterData()
            },
            deep : true
        }
    },
    methods: {
        filterData: function () {
            this.transactions = this.transactionsAux

            this.transactions = this.transactions.filter((transaction) => {
                return transaction.ammount.toString().toLowerCase().includes(this.query.toLowerCase()) || transaction.catalogPaymentMethod.code.toLowerCase().includes(this.query.toLowerCase()) || transaction.transaction_requirement_per_user_id.toString().includes(this.query.toLowerCase()) || transaction.total.toString().toLowerCase().includes(this.query.toLowerCase()) 
            })
        },
        goToRegisterPayment: function (transaction_requirement_per_user_id) {
            window.location.href = `../../apps/wallet/registration?trpid=${transaction_requirement_per_user_id}`
        },
        getAllTransactionsRequirement: function () {
            this.User.getAllTransactionsRequirement({}, (response) => {
                if (response.s == 1) {
                    this.transactionsAux = response.transactions
                    this.transactions = this.transactionsAux
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
            this.getAllTransactionsRequirement()
        })
    },
}).mount('#app')
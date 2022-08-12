import { User } from '../../src/js/user.module.js?t=4'

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
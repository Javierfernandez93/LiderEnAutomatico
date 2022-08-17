import { User } from '../../src/js/user.module.js?t=4'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            transaction: {
                payment_reference: null
            },
            paymentFilled: false,
            status: null,
            STATUS: {   
                PENDING_FOR_REGISTRATION : 1,
                REGISTERED : 2,
            }
        }
    },
    watch: {
        transaction: {
            handler() {
                this.paymentFilled = this.transaction.payment_reference != ''
            },
            deep: true
        }
    },
    methods: {
        registerTransaction: function (transaction) {
            this.User.registerTransaction({transaction_requirement_per_user_id:transaction.transaction_requirement_per_user_id,payment_reference:transaction.payment_reference},(response)=>{
                if(response.s == 1)
                {
                    this.status = this.STATUS.REGISTERED
                }
            })
        },
        getTransactionRequirementForRegistration: function (transaction_requirement_per_user_id) {
            return new Promise((resolve) => {
                this.User.getTransactionRequirementForRegistration({transaction_requirement_per_user_id:transaction_requirement_per_user_id}, (response) => {
                    if (response.s == 1) {
                        this.transaction = response.transaction
                        
                        resolve(this.STATUS.PENDING_FOR_REGISTRATION)
                    } else if (response.r == 'NOT_AVIABLE') {
                        resolve(this.STATUS.REGISTERED)
                    }
                })
            })
        },
    },
    mounted() {
        if(getParam('trpid'))
        {
            this.getTransactionRequirementForRegistration(getParam('trpid')).then((status) => {
                this.status = status

                console.log()
            })
        }
    },
}).mount('#app')
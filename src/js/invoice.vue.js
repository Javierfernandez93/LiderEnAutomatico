import { User } from '../../src/js/user.module.js?t=4.1.1'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            transaction: null
        }
    },
    watch: {
    },
    methods: {
        copy: function (text,event) {
            console.log(event.target)
            navigator.clipboard.writeText(text).then(() => {
                event.target.innerText = "copiado"
            });
        },
        goToRegistration: function (transaction_requirement_per_user_id) {
            window.location.href = `../../apps/wallet/registration?trpid=${transaction_requirement_per_user_id}`
        },
        getTransactionRequirementInvoice: function (transaction_requirement_per_user_id) {
            return new Promise((resolve) => {
                this.User.getTransactionRequirementInvoice({transaction_requirement_per_user_id:transaction_requirement_per_user_id}, (response) => {
                    if (response.s == 1) {
                        this.transaction = response.transaction
                    }

                    resolve()
                })
            })
        },
    },
    mounted() {
        this.getTransactionRequirementInvoice(getParam('trpid')).then(() => {
            
        })
    },
}).mount('#app')
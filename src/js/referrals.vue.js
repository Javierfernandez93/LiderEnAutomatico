import { User } from './user.module.js'

Vue.createApp({
    components: {

    },
    data() {
        return {
            referrals: {},
            referralsAux: {},
            query: null,
            totals: {
                total_capital: 0
            },
            User: null
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
            this.referrals = this.referralsAux

            this.referrals = this.referrals.filter((referral) => {
                return referral.names.toLowerCase().includes(this.query.toLowerCase()) || referral.company_id.toString().includes(this.query.toLowerCase()) || referral.email.toString().includes(this.query.toLowerCase()) 
            })
        },
        getTotals: function () {
            this.referrals.map((user)=>{
                this.totals.total_capital += user.plan ? parseFloat(user.plan.ammount) : 0
            })
        },
        getReferrals: function () {
            return new Promise((resolve) => {
                this.User.getReferrals({}, (response) => {
                    if (response.s == 1) {
                        this.referralsAux = response.referrals
                        this.referrals = this.referralsAux

                        resolve()
                    }
                })
            })
        }
    },
    mounted() {
        console.log(1)
        this.User = new User

        this.getReferrals().then(()=>{
            this.getTotals()
        })
    },
}).mount('#app')
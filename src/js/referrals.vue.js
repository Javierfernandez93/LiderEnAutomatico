import { User } from './user.module.js'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            referrals: {},
            referralsAux: {},
            workingDays: 0,
            query: null,
            totals: {
                totalEstimatedGains: 0,
                total_capital: 0
            }
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
        calculateProfit: function () {
            this.referrals.map((user)=>{
                user.estimatedGain = ((parseFloat(user.plan.additional_profit) + parseFloat(user.plan.profit) / this.workingDays) / 100) * user.plan.ammount

                return user
            })
        },
        getTotals: function () {
            this.referrals.map((user)=>{
                this.totals.total_capital += user.plan ? parseFloat(user.plan.ammount) : 0
                this.totals.totalEstimatedGains += user.plan ? parseFloat(user.estimatedGain) : 0
            })
        },
        getReferrals: function () {
            return new Promise((resolve) => {
                this.User.getReferrals({}, (response) => {
                    if (response.s == 1) {
                        this.referralsAux = response.referrals
                        this.workingDays = response.workingDays
                        this.referrals = this.referralsAux

                        resolve()
                    }
                })
            })
        }
    },
    mounted() {
        this.getReferrals().then(()=>{
            this.calculateProfit()
            this.getTotals()
        })
    },
}).mount('#app')
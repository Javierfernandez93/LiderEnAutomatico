import { UserSupport } from '../../src/js/userSupport.module.js?t=4.1.3'

/* vue */

Vue.createApp({
    components : { 
    },
    data() {
        return {
            UserSupport : new UserSupport,
            transactions : {},
            columns: { // 0 DESC , 1 ASC 
                user_support_id : {
                    name: 'user_support_id',
                    desc: false,
                },
                names : {
                    name: 'names',
                    desc: false,
                    alphabetically: true,
                },
                ammount : {
                    name: 'ammount',
                    desc: false,
                },
                method : {
                    name: 'method',
                    desc: false,
                },
                account : {
                    name: 'account',
                    desc: false,
                },
                create_date : {
                    name: 'create_date',
                    desc: false,
                },
            }
        }
    },
    watch : {
    },
    methods: {
        sortData: function (column) {
            this.administrators.sort((a,b) => {
                const _a = column.desc ? a : b
                const _b = column.desc ? b : a

                if(column.alphabetically)
                {
                    return _a[column.name].localeCompare(_b[column.name])
                } else {
                    return _a[column.name] - _b[column.name]
                }
            });

            column.desc = !column.desc
        },
        applyWithdraw : function(user_login_id) {
            this.UserSupport.applyWithdraw({user_login_id: user_login_id},(response)=>{
                if(response.s == 1)
                {
                    this.getUsersTransactions()
                }
            });
        },
        deleteWithdraw : function(withdraw_per_user_id,user_login_id) {
            console.log(1)
            this.UserSupport.deleteWithdraw({withdraw_per_user_id:withdraw_per_user_id,user_login_id:user_login_id},(response)=>{
                if(response.s == 1)
                {
                    this.getUsersTransactions()
                }
            })
        },
        getUsersTransactions : function() {
            this.UserSupport.getUsersTransactions({},(response)=>{
                if(response.s == 1)
                {
                    this.transactions = response.transactions
                }
            })
        },
    },
    mounted() 
    {
        this.getUsersTransactions()
    },
}).mount('#app')
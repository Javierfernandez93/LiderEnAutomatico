import { User } from '../../src/js/user.module.js?t=4.1.2'

/* vue */ 
import { NoticeViewer } from '../../src/js/noticeViewer.vue.js?t=4.1.2'
import { ProfitViewer } from '../../src/js/profitViewer.vue.js?t=4.1.2'
import { AfiliateViewer } from '../../src/js/afiliateViewer.vue.js?t=4.1.2'
import { AccountViewer } from '../../src/js/accountViewer.vue.js?t=4.1.2'

Vue.createApp({
    components : { 
        ProfitViewer, NoticeViewer, AfiliateViewer, AccountViewer
    },
    data() {
        return {
            User : new User,
            landing : null,
            user: null
        }
    },
    watch : {
        user : {
            handler() {
                
            },
            deep: true
        },
    },
    methods: {
        getProfile : function() {
            this.User.getProfile({},(response)=>{
                if(response.s == 1)
                {
                    this.user = response.user
                }
            })
        }
    },
    mounted() 
    {
        this.getProfile()
    },
}).mount('#app')
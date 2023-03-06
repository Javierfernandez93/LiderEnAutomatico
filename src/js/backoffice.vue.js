import { User } from '../../src/js/user.module.js?t=5.1.4'

/* vue */ 
import { NoticeViewer } from '../../src/js/noticeViewer.vue.js?t=5.1.4'
import { ProfitViewer } from '../../src/js/profitViewer.vue.js?t=5.1.4'
import { AfiliateViewer } from '../../src/js/afiliateViewer.vue.js?t=5.1.4'
import { AccountViewer } from '../../src/js/accountViewer.vue.js?t=5.1.4'

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
import { User } from '../../src/js/user.module.js?t=4.1.2'

Vue.createApp({
    components : { 
        
    },
    data() {
        return {
            catalogPlans: {},
            catalog_plan_id: null,
        }
    },
    watch : {
        user : {
            handler() {
                this.editProfile()
            },
            deep: true
        },
    },
    methods: {
        getMyRange : function() {
            this.User.getMyRange({},(response)=>{
                if(response.s == 1)
                {
                    this.catalogPlans = response.catalogPlans
                    this.catalog_plan_id = response.catalog_plan_id
                }
            })
        },
    },
    mounted() 
    {
        this.User = new User
        this.getMyRange()
    },
}).mount('#app')
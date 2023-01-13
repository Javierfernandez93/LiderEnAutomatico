import { User } from '../../src/js/user.module.js?t=4.1.1'

/* vue */ 
import { ToolsViewer } from '../../src/js/toolsViewer.vue.js?t=2'

Vue.createApp({
    components : { 
        ToolsViewer
    },
    data() {
        return {
            User : new User,
        }
    },
    methods: {
    },
    mounted() 
    {
    },
}).mount('#app')
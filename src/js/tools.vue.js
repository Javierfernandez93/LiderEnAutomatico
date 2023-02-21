import { User } from '../../src/js/user.module.js?t=5'

/* vue */ 
import { ToolsViewer } from '../../src/js/toolsViewer.vue.js?t=5.1.3'

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
import { FxwinningViewer } from '../../src/js/fxwinningViewer.vue.js?t=4'

Vue.createApp({
    components : { 
         FxwinningViewer
    },
    data() {
        return {
            backoffice: false,
        }
    },
    mounted() {
        this.$refs.fxwinning.init()
    }
}).mount('#app')
import { SignupViewer } from '../../src/js/signupViewer.vue.js?t=4'
import { FxwinningViewer } from '../../src/js/fxwinningViewer.vue.js?t=4'

Vue.createApp({
    components : { 
        SignupViewer, FxwinningViewer
    },
    data() {
        return {
            step: 1,
            backoffice: true
        }
    },
    methods: {
        setStep(step) {
            this.step = step

            if(this.step === 2)
            {
                this.$refs.fxwinning.init()
            }
        }
    },
}).mount('#app')
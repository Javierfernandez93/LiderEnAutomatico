import { User } from '../../src/js/user.module.js?t=4.1.1'   

const AfiliateViewer = {
    name : 'afiliate-viewer',
    data() {
        return {
            User : new User,
            landing: null
        }
    },
    methods: {
        getBackofficeVars() {
            this.User.getBackofficeVars({},(response)=>{
                if(response.s == 1)
                {
                    this.landing = response.landing
                }
            })
        },
        copyLandingButton(link,event) {
            this.copyLanding(link)

            event.target.innerText = 'Link copiado con éxito'
        },
        copyLanding(link) {
            navigator.clipboard.writeText(link).then(() => {
                this.$refs.landingHelper.innerText = 'Link copiado con éxito'
            });
        },
    },
    updated() {
    },
    mounted() 
    {   
        this.getBackofficeVars()
    },
    template : `
        <div class="card bg-gradient-success mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="badge bg-white text-success fs-4"><i class="bi bi-person-bounding-box"></i></span>
                    </div>
                    <div class="col text-white">
                        <div ref="landingHelper" class="text-white fw-semibold">Mi link de afiliado</div>
                        <span @click="copyLanding(landing)" class="fw-semibold cursor-pointer">
                            <u>{{landing}}</u>
                        </span>
                    </div>
                    <div class="col-auto">
                        <button
                            @click="copyLandingButton(landing,$event)"
                            class="btn btn-light m-0 ms-2">
                            Copiar link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
}

export { AfiliateViewer } 
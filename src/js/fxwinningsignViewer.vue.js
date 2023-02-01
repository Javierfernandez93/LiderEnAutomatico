import { Fxwinning } from './fxwinning.module.js?t=4'   

const FxwinningsignViewer = {
    name : 'fxwinningsign-viewer',
    data() {
        return {
            Fxwinning : new Fxwinning,
            userComplete: false,
            LANDSCAPE_RATIO: 1.4,
            loading: false,
            saved: false,
            orientation: null,
            canvas: null,
            signaturePad: null,
            loading: false,
            user: {
                signature: null,
                user_login_id: null
            },
            ORIENTATION : {
                PORTRAIT : 0,
                LANDSCAPE : 90,
            }
        }
    },
    watch : {
        user : {
            handler() {
              this.userComplete = this.user.user_login_id
            },
            deep: true
        },
    },
    methods: {
        clean() {
            this.signaturePad.clear();
        },
        resizeCanvas() {
            // let ratio = this.orientation == this.ORIENTATION.PORTRAIT ? Math.max(window.devicePixelRatio || 1, 1) : this.LANDSCAPE_RATIO;
            let ratio = this.orientation == this.ORIENTATION.PORTRAIT ? 1 : this.LANDSCAPE_RATIO;
            // ratio = this.LANDSCAPE_RATIO
            
            this.canvas.width = this.canvas.offsetWidth * ratio;
            this.canvas.height = this.canvas.offsetHeight * ratio;

            if(this.orientation == this.ORIENTATION.PORTRAIT)
            {
                this.canvas.getContext("2d").scale(ratio, ratio)
            }

            this.signaturePad.clear(); // otherwise isEmpty() might return incorrect value
        },        
        clearSignature() 
        {
            this.signaturePad.clear();
        },
        saveSignatureInAccount() 
        {
            this.Fxwinning.saveSignatureInAccount(this.user,(response)=>{
                if(response.s == 1)
                {
                    this.saved = true
                }
            })
        },
        saveSignature() 
        {
            const image = this.signaturePad.toDataURL('image/png');
            this.loading = true
            
            this.Fxwinning.uploadImageSignAsString({image:image},(response)=>{
                this.loading = false
                if(response.s == 1)
                {
                    this.user.signature = response.target_path

                    this.saveSignatureInAccount()
                }
            })
        },
        initSignature() 
        {
            this.canvas = document.getElementById('signature-pad')

            this.signaturePad = new SignaturePad(this.canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(0, 0, 0)'
            });
        },
        getuserBySignCode() 
        {
            return new Promise((resolve,reject) => {
                this.Fxwinning.getuserBySignCode({sign_code:getParam("s")},(response)=>{
                    if(response.s == 1)
                    {
                        resolve(response.user_login_id)
                    }

                    reject()
                })
            })
        },       
        init() 
        {
            setTimeout(()=>{
                this.initSignature()
        
                window.addEventListener("resize", this.resizeCanvas());
    
                this.resizeCanvas()
                
                zoomOutMobile()
            },500)
        },
    },
    mounted() 
    {   
        this.orientation = window.orientation

        $(window).on('orientationchange', (event) => {
            this.orientation = window.orientation

            this.resizeCanvas()
        });

        this.getuserBySignCode().then((user_login_id) => {
            this.user.user_login_id = user_login_id
            
            this.init()
        }).catch((error) => {
            this.user.user_login_id = false
        })
    },
    template : `           
        <div v-if="user.user_login_id">
            <div v-if="!saved">
                <div class="card bg-light">
                    <div v-if="orientation == ORIENTATION.PORTRAIT" class="alert alert-info text-white">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-phone-landscape h3"></i>
                            </div>
                            <div class="col">
                                <div><strong>Aviso importante</strong></div>
                                Por favor rote su celular para tener una mejor firma
                            </div>
                        </div>
                    </div>

                    <div v-show="orientation == ORIENTATION.LANDSCAPE" class="card-body p-0 d-flex justify-content-center">
                        <canvas class="border border border-primary bg-white rounded-2" id="signature-pad"></canvas>
                    </div>
                </div>
                <div class="row justify-content-center py-3">
                    <div class="col-11 col-xl-3">
                        <div class="row">
                            <div class="col-6 d-grid col-xl-6">
                                <button :disabled="loading" @click="saveSignature" class="btn btn-primary shadow-none">
                                    <span v-if="!loading">
                                        Enviar firma
                                    </span>
                                    <span v-else>
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="col-6 d-grid col-xl-6">
                                <button @click="clean" class="btn btn-danger shadow-none">Borrar firma</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="row justify-content-center">
                <div class="col-12 col-xl-4">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body text-center h5">
                            <div>
                                <i class="bi h2 text-white bi-check-circle-fill"></i>
                            </div>

                            <div>Gracias</div>
                            <div>Tu firma ha sido guardada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="user.user_login_id == false" class="alert text-white alert-info text-center">
            <div><strong>Aviso</strong></div>
            El link proporcionado no es válido
        </div>
    `,
}

export { FxwinningsignViewer } 
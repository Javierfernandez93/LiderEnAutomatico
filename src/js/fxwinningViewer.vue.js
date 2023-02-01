import { User } from '../../src/js/user.module.js?t=4'   
import { debounce } from './util.module.js?t=4'

const FxwinningViewer = {
    name : 'fxwinning-viewer',
    props: ['backoffice'],
    data() {
        return {
            User : new User,
            userComplete: false,
            document: null,
            remote: {
                url: null
            },
            canvas: null,
            signaturePad: null,
            loading: false,
            user: {
                email: null,
                names: null,
                id_number: null,
                address: null,
                signature: null,
                investor: {
                    number: null
                }
            },
        }
    },
    watch : {
        user : {
            handler() {
              this.userComplete = this.user.id_number != null && this.user.address != null && this.user.investor.number != null && this.user.signature != null

              this.saveUserDataTemporally()
            },
            deep: true
        },
    },
    methods: {
        copyLink(url,target) 
        {
            navigator.clipboard.writeText(url).then(() => {
                target.innerText = 'Copiado'
            });
        },
        sendWhatsApp(url) 
        {
            window.open(`😊 Hola, por favor firma tu documento LPOA de Libertad Financiera dando click aquí 👉 ${url}`.getWhatsappLink())
        },
        generateLinkForSignature() 
        {
            this.User.generateLinkForSignature({},(response)=>{
                if(response.s == 1)
                {
                    this.remote.url = response.url
                }
            })
        },
        uploadFile() 
        {
            $(".progress").removeClass("d-none")

            let files = $(this.$refs.file).prop('files');
            var form_data = new FormData();
          
            form_data.append("file", files[0]);
          
            this.User.uploadImageSign(form_data,$(".progress-chat").find(".progress-bar"),(response)=>{
              if(response.s == 1)
              {
                  this.user.signature = response.target_path
              }
            });
        },
        makeFxWinninDocument() 
        { 
            this.User.makeFxWinninDocument(this.user,(response)=>{
              if(response.s == 1)
              {
                this.document = response.path
              }
            });
        },
        saveUserDataTemporally() 
        { 
            this.User.saveUserDataTemporally(this.user,(response)=>{
              if(response.s == 1)
              {
                
              }
            });
        },
        resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            
            this.canvas.width = this.canvas.offsetWidth * ratio;
            this.canvas.height = this.canvas.offsetHeight * ratio;

            this.canvas.getContext("2d").scale(ratio, ratio);

            this.signaturePad.clear(); // otherwise isEmpty() might return incorrect value
        },        
        clearSignature() 
        {
            this.signaturePad.clear();
        },
        saveSignature() 
        {
            const image = this.signaturePad.toDataURL('image/png');
            
            this.User.uploadImageSignAsString({image:image},(response)=>{
                if(response.s == 1)
                {
                    this.user.signature = response.target_path
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
        getUserSignature() 
        {
            this.User.getUserSignature({},(response)=>{
                if(response.s == 1)
                {
                    this.user.signature = response.signature
                }
            })
        },
        init() 
        {
            setTimeout(()=>{
                this.initSignature()
                this.getUserSignature()

                this.mergeProfile().then(()=>{
        
                    window.addEventListener("resize", this.resizeCanvas());
        
                    this.resizeCanvas()
                })
            },1000)
        },
        mergeProfile() 
        { 
            return new Promise((resolve)=>{
                this.User.getProfile(this.user,(response)=>{
                    if(response.s == 1)
                    {
                        this.user = {
                            ...this.user,
                            ...response.user
                        }
                    }
                })
            })
        },
    },
    mounted() 
    {   
    },
    template : `
        <div class="col-12 col-xl-6 animate__animated animate__bounceInRight">
            <div class="row justify-content-center text-center">
                <div class="col-11 col-xl-10">
                    <div class="card text-start shadow p-3">
                        <div class="card-header pb-0 text-left bg-transparent">
                            <h3 class="font-weight-bolder text-info text-gradient">Genera tu documento</h3>
                            <h3 class="text-secondary text-xs">(*) campos requeridos</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                                    <label>* Nombre completo</label>
                                    <div class="mb-3">
                                        <input 
                                            :class="user.names ? 'is-valid' : ''"
                                            :autofocus="true" type="text" ref="names" v-model="user.names" class="form-control" @keydown.enter.exact.prevent="$refs.email.focus()" placeholder="Escribe aquí" aria-label="Escribe aquí" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="col-12 col-xl-6">
                                    <label>* Correo electrónico</label>
                                    <div class="mb-3">
                                        <input 
                                            :class="user.email ? 'is-valid' : ''"
                                            :autofocus="true" type="text" ref="email" v-model="user.email" class="form-control" @keydown.enter.exact.prevent="$refs.id_number.focus()" placeholder="Escribe aquí" aria-label="Escribe aquí" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                                    <label>* Número de identificación</label>
                                    <div class="mb-3">
                                        <input 
                                            :class="user.id_number ? 'is-valid' : ''"
                                            :autofocus="true" type="text" ref="id_number" v-model="user.id_number" class="form-control" @keydown.enter.exact.prevent="$refs.address.focus()" placeholder="Escribe aquí" aria-label="Escribe aquí" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                                    <label>* Account Number (MI5):</label>
                                    <div class="mb-3">
                                        <input 
                                            :class="user.investor.number ? 'is-valid' : ''"
                                            :autofocus="true" type="text" ref="number" v-model="user.investor.number" class="form-control" @keydown.enter.exact.prevent="$refs.phone.focus()" placeholder="Escribe aquí" aria-label="Escribe aquí" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 col-xl-12 mb-3 mb-xl-0">
                                    <label>* Dirección completa</label>
                                    <div class="mb-3">
                                        <input 
                                            :class="user.address ? 'is-valid' : ''"
                                            :autofocus="true" type="text" ref="address" v-model="user.address" class="form-control" @keydown.enter.exact.prevent="$refs.number.focus()" placeholder="Escribe aquí" aria-label="Escribe aquí" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>


                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Hacer firma dígital</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button @click="generateLinkForSignature" class="nav-link" id="pills-remote-tab" data-bs-toggle="pill" data-bs-target="#pills-remote" type="button" role="tab" aria-controls="pills-remote" aria-selected="false">Firma remota</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Subir imagen</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div class="card">
                                        
                                        <div class="card-body p-0 d-flex justify-content-center">
                                            <canvas class="border rounded-2" id="signature-pad"></canvas>
                                        </div>

                                        <div class="text-center text-xs text-secondary">Dibuja tu firma dentro del recuadro lo más parecido a tu firma oficial</div>

                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 col-xl-6">
                                                    <button @click="saveSignature" class="btn btn-outline-primary w-100">Guardar</button>
                                                </div>
                                                <div class="col-12 col-xl-6">
                                                    <button @click="clearSignature" class="btn btn-outline-danger w-100">Limpiar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="border rounded-2 text-center cursor-pointer position-relative p-3 mb-3">
                                        <div class="fw-semibold text-dark">
                                            <span v-text="user.signature ? 'Cambiar firma' : 'Sube tu firma aquí'"></span>
                                        </div>

                                        <div class="text-xs">* formato JPEG con fondo blanco</div>
                                        
                                        <input class="opacity-0 cursor-pointer bg-dark w-100 h-100 start-0 top-0 position-absolute" ref="file" @change="uploadFile($event)" capture="filesystem" type="file" accept=".jpg, .png, .jpeg" />
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-remote" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div v-if="remote.url" class="row align-items-center mb-3">
                                        <div class="col-8">
                                            <div class="badge text-secondary p-0">Liga para firma remota</div>
                                            <a :href="remote.url" target="_blank">{{ remote.url }}</a>
                                        </div>
                                        <div class="col-2 d-grid">
                                            <button @click="copyLink(remote.url,$event.target)" class="btn btn-outline-primary px-3 btn-sm">Copiar</button>
                                        </div>
                                        <div class="col-2 d-grid">
                                            <button @click="sendWhatsApp(remote.url)" class="btn btn-outline-success px-3 btn-sm"><i class="bi fs-5 bi-whatsapp"></i></button>
                                        </div>
                                    </div>

                                    <div class="alert alert-light text-center">
                                        <div class="mb-3"><strong>¿Ya realizaste tu firma con la liga remota?</strong></div>
                                        <button @click="getUserSignature" class="btn btn-outline-primary px-3 btn-sm">Da click aquí para actualizar</button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="user.signature">
                                <div class="text-center">Tu firma es la siguiente:</div>
                                <img :src="user.signature" class="img-fluid img-thumbnail" title="signature" />
                            </div>
                            

                            <button :disabled="!userComplete || loading" class="btn bg-primary shadow-none text-white w-100 mt-4 mb-0" @click="makeFxWinninDocument" id="button">
                                <span v-if="!loading">
                                    Generar documento
                                </span>
                                <span v-else>
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </span>
                            </button>
                            
                            <div v-if="document" class="row">
                                <div v-if="backoffice" class="col-12 col-xl">
                                    <a href="../../apps/backoffice/" class="btn bg-primary shadow-none text-white w-100 mt-4 mb-0" @click="makeFxWinninDocument" id="button">
                                        Ir a mi oficina virtual
                                    </a>
                                </div>
                                <div class="col-12 col-xl">
                                    <a :href="document" targert="_blank" download class="btn bg-primary shadow-none text-white w-100 mt-4 mb-0" @click="makeFxWinninDocument" id="button">
                                        Descargar Archivo
                                    </a>
                                </div>
                            </div>
                        </div>    
                    </div>    
                </div>
            </div>
        </div>
    `,
}

export { FxwinningViewer } 
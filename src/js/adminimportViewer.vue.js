import { UserSupport } from './userSupport.module.js?t=5.1.3'   

const AdminimportViewer = {
    name : 'stats-viewer',
    data() {
        return {
            UserSupport : new UserSupport,
            users: null,
            loading: false,
            referral: {
                user_login_id: null
            },
            file: null,
            STATUS: {
                CREATED: {
                    code : 1,
                    text : 'Creado correctamente',
                },
                ALREADY_EXIST: {
                    code : 2,
                    text : 'Ya éxiste en la base de datos',
                },
                ERROR: {
                    code : -1,
                    text : 'Error al crear usuario',
                }
            }
        }
    },
    methods: {
        generateSafePassword(names) {
            names = names.normalize("NFD").replace(/[\u0300-\u036f]/g, "")

            return names.split(" ")[0]+"2023"
        },
        getLada(phone) {
            const end = phone.length - 8  

            return phone.slice(0, end)
        },
        addUser(user) {
            if(typeof user.phone == 'string')
            {
                user.phone = user.phone.replace(/\D/g, "")
                user.lada = this.getLada(user.phone)
            }

            user.password = this.generateSafePassword(user.names)

            this.UserSupport.saveUser({user:{...user, ...{referral:this.referral}}},(response)=>{
                
                if(response.s == 1)
                {
                    user.status = this.STATUS.CREATED.code
                } else if(response.r == 'MAIL_ALREADY_EXISTS') {
                    user.status = this.STATUS.ALREADY_EXIST.code
                } else {
                    user.status = this.STATUS.ERROR.code
                }
            })
        },
        async addUsers() 
        {
            let alert = alertCtrl.create({
                title: "Aviso",
                subTitle: `¿Estás seguro de estos ${this.users.length} usuarios?`,
                buttons: [
                    {
                        text: "Sí, añadir",
                        role: "cancel",
                        class: 'btn-success',
                        handler: async (data) => {
                            this.loading = true
                            for (const user of this.users) {
                                await this.addUser(user)
                            }
                            this.loading = false
                        },
                    },
                    {
                        text: "Cancelar",
                        role: "cancel",
                        handler: (data) => {
                        },
                    },
                ],
            })

            alertCtrl.present(alert.modal); 
        },
        uploadFile() 
        {
            $(".progress").removeClass("d-none")

            let files = $(this.$refs.file).prop('files');
            var form_data = new FormData();
          
            form_data.append("file", files[0]);
          
            this.UserSupport.uploadXLSFile(form_data,$(".progress-chat").find(".progress-bar"),(response)=>{
              if(response.s == 1)
              {
                this.file = response.target_path
                this.readFileData()
              }
            });
        },
        readFileData() {    
            this.UserSupport.readFileData({file:this.file},(response)=>{
                if(response.s == 1)
                {
                    this.users = response.users
                }
            })
        },
    },
    mounted() 
    {   
        
    },
    template : `
        <div class="card">
            <div class="card-body">
                <div class="border rounded-2 text-center cursor-pointer position-relative p-3 mb-3">
                    <div class="fw-semibold text-dark">
                        <span v-text="file ? 'Cambiar archivo' : 'Sube tu archivo aquí'"></span>
                    </div>

                    <div class="text-xs">* formato XLS o XLSX</div>
                    
                    <input class="opacity-0 cursor-pointer bg-dark w-100 h-100 start-0 top-0 position-absolute" ref="file" @change="uploadFile($event)" capture="filesystem" type="file" accept=".xls, .xlsx" />
                </div>
                <div class="alert text-center alert-info text-white"><strong>Aviso</strong> El template para subir tus usuarios debe de ser <a class="text-decoration-underline text-success" href="../../src/files/excel/template.xlsx">igual a éste</a></div>
            </div>

            <div v-if="users">
                <div class="card-header">
                    <div class="row align-items-end">
                        <div class="col">
                            <div class="pb-3 text-secondary">Total de usuarios {{users.length}}</div>

                            <input class="form-control" v-model="referral.user_login_id" placeholder="ID de patrocinador"/>
                        </div>
                        <div class="col-auto">
                            <button :disabled="!referral.user_login_id || loading" @click="addUsers" class="btn mb-0 shadow-none btn-primary">
                                <span v-if="loading">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span v-else>
                                    Crear usuarios
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li v-for="(user,index) in users" class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                {{index+1}}
                            </div>
                            <div class="col h5 text-dark">
                                {{user.names}}
                            </div>
                            <div class="col-auto">
                                {{user.email}}
                            </div>
                            <div v-if="user.password && user.status == STATUS.CREATED.code" class="col-auto">
                                {{user.password}}
                            </div>
                            <div v-if="user.status" class="col-auto">
                                <span v-if="user.status == STATUS.CREATED.code" class="badge bg-success">
                                    {{STATUS.CREATED.text}}
                                </span>
                                <span v-else-if="user.status == STATUS.ALREADY_EXIST.code" class="badge bg-primary" >
                                    {{STATUS.ALREADY_EXIST.text}}
                                </span>
                                <span v-else-if="user.status == STATUS.ERROR.code" class="badge bg-danger">
                                    {{STATUS.ERROR.text}}
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    `,
}

export { AdminimportViewer } 
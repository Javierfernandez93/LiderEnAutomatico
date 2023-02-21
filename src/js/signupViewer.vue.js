import { User } from '../../src/js/user.module.js?t=5.1.3'   

const SignupViewer = {
    name : 'signup-viewer',
    emits : ['setstep'],
    data() {
        return {
            User : new User,
            passwordFeedback: null,
            user: {
                email: null,
                phone: null,
                names: null,
                country_id: 159, // loads by default México
                passwordAgain: null,
                password: null,
                referral: {
                    user_login_id: 0,
                    names: '',
                    image : ''
                },
                utm: false,
            },
            countries : {},
            loading : false,
            feedback : false,
            isValidMail : false,
            passwordsMatch : null,
            fieldPasswordType : 'password',
            userComplete : false,
        }
    },
    watch : {
        user : {
            handler() {
                this.checkEmail()
                this.checkFields()
                this.checkPasswords()
            },
            deep: true
        },
    },
    methods: {
        getReferral(user_login_id) {
            this.feedback = false

            this.User.getReferral({user_login_id:user_login_id,utm:this.user.utm},(response)=>{
                if(response.s == 1)
                {
                   Object.assign(this.user.referral,response.referral)
                } else if(response.r == "NOT_DATA") {
                    this.feedback = "No encontramos información del link de referido proporcionado"
                }
            })
        },
        toggleFieldPasswordType() {
            this.fieldPasswordType = this.fieldPasswordType == 'password' ? 'text' : 'password'
        },
        doSignup() {
            this.loading = true
            this.feedback = false
            
            this.User.doSignup(this.user,(response)=>{
                this.loading = false

                if(response.s == 1)
                {
                    if(getParam('fxwinning'))
                    {
                        this.$emit("setstep",2)
                    } else {
                        window.location.href = '../../apps/backoffice/fxwinning'
                    }
                } else if(response.r == "MAIL_ALREADY_EXISTS") {
                    this.feedback = 'El correo proporcionado ya existe'
                }
            })
        },
        getCountries() {
            this.User.getCountries(this.user,(response)=>{
                if(response.s == 1)
                {
                    this.countries = response.countries
                }
            })
        },
        checkEmail() {
            this.isValidMail = isValidMail(this.user.email)
        },
        getUtm() {
            if(window.location.pathname.split('/').inArray('join') != -1) {
                this.user.utm = 'join'
            }
        },
        checkPasswords() {
            if(this.user.password != null && this.user.passwordAgain != null)
            {
                if(this.user.passwordAgain != this.user.password)   
                {
                    this.passwordFeedback = `<span class="text-danger fw-bold"><i class="bi bi-patch-exclamation"></i> Las contraseñas no coinciden</span>`
                } else {
                    this.passwordFeedback = '<span class="text-success fw-bold"><i class="bi bi-patch-check"></i> Las contraseñas coinciden</span>'
                }
            }
        },
        checkFields() {
            this.userComplete = this.isValidMail && this.user.password && this.user.phone && this.user.names
        }
    },
    mounted() 
    {   
        $(this.$refs.phone).mask('(00) 0000-0000');

        this.getCountries()
        this.getUtm() // getting campaign

        if(getParam('uid'))
        {
            this.getReferral(getParam('uid'))
        }
    },
    template : `
        <div class="col-12 col-xl-6 img-bg bg-primary order-1">
            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../../src/img/business-3.jpg')"></div>
            </div>
        </div>
        <div class="col-12 col-xl-6 animate__animated animate__bounceInLeft">
            <div class="row justify-content-center text-center">
                <div class="col-11 col-xl-7">
                    <div class="card text-start shadow-none p-3">
                        <div class="card-header pb-0 text-left bg-transparent">
                            <h3 class="font-weight-bolder text-info text-gradient">Bienvenido a Libertad en Automático</h3>
                            <p class="mb-0">Ingresa tus datos para unirte</p>
                        </div>
                        <div class="card-body">
                            <label>Nombre</label>
                            <div class="mb-3">
                                <input 
                                    :class="user.names ? 'is-valid' : ''"
                                    :autofocus="true" type="text" ref="names" v-model="user.names" class="form-control" @keydown.enter.exact.prevent="$refs.phone.focus()" placeholder="Nombre" aria-label="Nombre" aria-describedby="basic-addon1">
                            </div>

                            <label>Teléfono</label>
                            <div class="row">
                                <div class="col">
                                    <select class="form-select" v-model="user.country_id" aria-label="Selecciona tu país">
                                        <option>Selecciona tu país</option>
                                        <option v-for="country in countries" v-bind:value="country.country_id">
                                            {{ country.nicename }} <span v-if="country.phone_code > 0">+ {{ country.phone_code }}</span>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <input 
                                            :class="user.phone ? 'is-valid' : ''"
                                            type="text" ref="phone" v-model="user.phone" class="form-control" @keydown.enter.exact.prevent="$refs.email.focus()" placeholder="Teléfono" aria-label="Teléfono" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            
                            <label>Correo electrónico</label>
                            <div class="mb-3">
                                <input 
                                    :class="isValidMail ? 'is-valid' : ''"
                                    type="email" ref="email" v-model="user.email" class="form-control" @keydown.enter.exact.prevent="$refs.password.focus()" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
                            </div>

                            <label>Contraseña</label>
                            <div class="input-group">
                                <input 
                                    :class="user.password ? 'is-valid' : ''"
                                    :type="fieldPasswordType" 
                                    ref="password" 
                                    @keydown.enter.exact.prevent="$refs.passwordAgain.focus()" 
                                    v-model="user.password" 
                                    style="height:41px;" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                <button class="btn btn-secondary" type="button" id="button-addon2" @click="toggleFieldPasswordType">
                                    <i v-if="fieldPasswordType == 'password'" class="bi bi-eye"></i>
                                    <i v-else class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            
                            <label>Contraseña de nuevo</label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input 
                                        :class="user.password != null && user.password == user.passwordAgain ? 'is-valid' : 'is-invalid'"
                                        :type="fieldPasswordType" 
                                        ref="passwordAgain" 
                                        @keydown.enter.exact.prevent="doSignup" 
                                        v-model="user.passwordAgain" 
                                        style="height:41px;" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                    <button class="btn btn-secondary" type="button" id="button-addon2" @click="toggleFieldPasswordType">
                                        <i v-if="fieldPasswordType == 'password'" class="bi bi-eye"></i>
                                        <i v-else class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                <small v-if="passwordFeedback != null" class="form-text text-muted" v-html="passwordFeedback">
                                </small>
                            </div>


                            <div
                                v-if="user.referral.user_login_id"
                                class="card card-body blur shadow-blur overflow-hidden">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar avatar-xl overflow-hidden img-upload position-relative">
                                            <img class="w-100 border-radius-lg shadow-sm" :src="user.referral.image"/>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="small text-muted">Referido por:</div>
                                        <div class="lead">
                                            <div class="fw-semibold">{{user.referral.names}}</div>
                                            <div class="text-muted">{{user.referral.email}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-show="feedback" class="alert alert-secondary text-white alert-dismissible fade show" role="alert">
                                <div><strong>Aviso</strong></div>
                                {{ feedback }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <button :disabled="!userComplete || loading" class="btn bg-primary shadow-none text-white w-100 mt-4 mb-0" @click="doSignup" id="button">
                                <span v-if="!loading">
                                    Crear mi cuenta
                                </span>
                                <span v-else>
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </span>
                            </button>

                        </div>    
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                                ¿Ya tienes una cuenta?
                                <a href="../../apps/login" class="text-info text-gradient font-weight-bold">Ingresa aquí</a>
                            </p>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    `,
}

export { SignupViewer } 
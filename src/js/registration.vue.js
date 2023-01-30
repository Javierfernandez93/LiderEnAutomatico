import { User } from '../../src/js/user.module.js?t=4.1.2'

Vue.createApp({
    components: {

    },
    data() {
        return {
            User: new User,
            transaction: {
                payment_reference: null,
                image: null
            },
            paymentFilled: false,
            whatsAppLink: null,
            status: null,
            STATUS: {   
                PENDING_FOR_REGISTRATION : 1,
                REGISTERED : 2,
            }
        }
    },
    watch: {
        transaction: {
            handler() {
                this.paymentFilled = this.transaction.payment_reference != '' && this.transaction.image != ''
            },
            deep: true
        }
    },
    methods: {
        goToWhatsAppSupport: function (transaction) {
            window.location.href = getWhatsAppLink('+573238131694',`¡Hola soy el usuario con ID ${transaction.user_login_id}!, necesito ayuda con mi fondeo nº ${transaction.transaction_requirement_per_user_id} en Libertad en Automático`)
        },
        registerTransaction: function (transaction) {
            this.User.registerTransaction({image:transaction.image,transaction_requirement_per_user_id:transaction.transaction_requirement_per_user_id,payment_reference:transaction.payment_reference},(response)=>{
                if(response.s == 1)
                {
                    this.status = this.STATUS.REGISTERED
                }
            })
        },
        openFileManager: function () 
        {
            this.$refs.file.click()
        },
        uploadFile: function () 
        {
            $(".progress").removeClass("d-none")

            let files = $(this.$refs.file).prop('files');
            var form_data = new FormData();
          
            form_data.append("file", files[0]);
          
            this.User.uploadImageFund(form_data,$(".progress-chat").find(".progress-bar"),(response)=>{
              if(response.s == 1)
              {
                  this.transaction.image = response.target_path
              }
            });
        },
        getTransactionRequirementForRegistration: function (transaction_requirement_per_user_id) {
            return new Promise((resolve) => {
                this.User.getTransactionRequirementForRegistration({transaction_requirement_per_user_id:transaction_requirement_per_user_id}, (response) => {
                    if (response.s == 1) {
                        this.transaction = response.transaction
                        
                        resolve(this.STATUS.PENDING_FOR_REGISTRATION)
                    } else if (response.r == 'NOT_AVIABLE') {
                        resolve(this.STATUS.REGISTERED)
                    }
                })
            })
        },
    },
    mounted() {
        if(getParam('trpid'))
        {
            this.getTransactionRequirementForRegistration(getParam('trpid')).then((status) => {
                this.status = status
                // this.status = this.STATUS.REGISTERED
            })
        }
    },
}).mount('#app')
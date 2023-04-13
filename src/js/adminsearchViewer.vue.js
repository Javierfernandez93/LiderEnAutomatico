import { UserSupport } from '../../src/js/userSupport.module.js?t=5.1.4'   

const AdminsearchViewer = {
    name : 'adminsearch-viewer',
    data() {
        return {
            UserSupport : new UserSupport,
            transactionFill: false,
            transaction: {
                id : null,
                data : null
            }
        }
    },
    watch: {
        'transaction.id' : {
            handler() {
                this.checkTransactionFill()
            },
            deep : true
        }
    },
    methods: {
        checkTransactionFill() {
            this.transactionFill = this.transaction.id != null || this.transaction.id != ''
        },
        searchTXIDCoinpayments() {
            this.UserSupport.searchTXIDCoinpayments({id:this.transaction.id},(response)=>{
                if(response.s == 1)
                {
                    this.transaction.data = response.data
                }
            })
        },
    },
    mounted() 
    {   
        if(getParam('id'))
        {
            this.transaction.id = getParam('id')
            this.checkTransactionFill()
        }
    },
    template : `
        <div class="card">
            <div class="card-header">
            {{transaction}}
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">ID de Coinpayments</span>
                    <input :class="transactionFill ? 'is-valid' : 'is-invalid'" @keydown.enter.exact.prevent="searchTXIDCoinpayments" :autocomplete="true" v-model="transaction.id" type="text" class="form-control" placeholder="Escribe aquí..." aria-label="Escribe aquí..." aria-describedby="basic-addon1">

                    <button :disabled="!transactionFill" class="btn btn-primary mb-0 shadow-none" @click="searchTXIDCoinpayments">Buscar</button>
                </div>
            </div>
        </div>
    `,
}

export { AdminsearchViewer } 
import { UserSupport } from '../../src/js/userSupport.module.js?t=4.1.3'

/* vue */

Vue.createApp({
    components: {
    },
    data() {
        return {
            UserSupport: null,
            catalogPaymentMethods: {},
            catalogPaymentMethodsAux: {},
            query: null,
            percentaje: 0,
            total: 0,
            total_profit_today: 0,
            total_profit_sponsor_today: 0,
            columns: { // 0 DESC , 1 ASC 
                catalog_payment_method_id: {
                    name: 'catalog_payment_method_id',
                    desc: false,
                },
                currency: {
                    name: 'currency',
                    desc: false,
                    alphabetically: true,
                },
                fee: {
                    name: 'fee',
                    desc: false,
                },
                recomended: {
                    name: 'recomended',
                    desc: false,
                },
                create_date: {
                    name: 'create_date',
                    desc: false,
                },
                status: {
                    name: 'status',
                    desc: false,
                },
            }
        }
    },
    watch: {
        query:
        {
            handler() {
                this.filterData()
            },
            deep: true
        }
    },
    methods: {
        sortData: function (column) {
            this.catalogPaymentMethods.sort((a, b) => {
                const _a = column.desc ? a : b
                const _b = column.desc ? b : a

                if (column.alphabetically) {
                    return _a[column.name].localeCompare(_b[column.name])
                } else {
                    return _a[column.name] - _b[column.name]
                }
            });

            column.desc = !column.desc
        },
        filterData: function () {
            this.catalogPaymentMethods = this.catalogPaymentMethodsAux

            this.catalogPaymentMethods = this.catalogPaymentMethods.filter((catalogPaymentMethod) => {
                return catalogPaymentMethod.code.toLowerCase().includes(this.query.toLowerCase()) || catalogPaymentMethod.currency.toLowerCase().includes(this.query.toLowerCase()) || catalogPaymentMethod.fee.toString().includes(this.query.toLowerCase()) || catalogPaymentMethod.description.includes(this.query.toLowerCase())
            })
        },
        toggleEditingFee: function (catalogPaymentMethod) {
            catalogPaymentMethod.editingFee = !catalogPaymentMethod.editingFee
        },
        savePaymentMethodFee: function (catalogPaymentMethod) {
            this.UserSupport.savePaymentMethodFee({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id, fee : catalogPaymentMethod.fee},(response)=>{
                if(response.s == 1)
                {
                    this.toggleEditingFee(catalogPaymentMethod)
                }
            })
        },
        inactivePaymentMethod: function (catalogPaymentMethod) {
            this.UserSupport.inactivePaymentMethod({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id},(response)=>{
                if(response.s == 1)
                {
                    catalogPaymentMethod.status = response.status
                }
            })
        },
        activePaymentMethod: function (catalogPaymentMethod) {
            this.UserSupport.activePaymentMethod({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id},(response)=>{
                if(response.s == 1)
                {
                    catalogPaymentMethod.status = response.status
                }
            })
        },
        enableRecomendation: function (catalogPaymentMethod) {
            this.UserSupport.enableRecomendation({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id},(response)=>{
                if(response.s == 1)
                {
                    catalogPaymentMethod.recomended = 1
                }
            })
        },
        disableRecomendation: function (catalogPaymentMethod) {
            this.UserSupport.disableRecomendation({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id},(response)=>{
                if(response.s == 1)
                {
                    catalogPaymentMethod.recomended = 0
                }
            })
        },
        deletePaymentMethod: function (catalogPaymentMethod) {
            this.UserSupport.deletePaymentMethod({catalog_payment_method_id: catalogPaymentMethod.catalog_payment_method_id},(response)=>{
                if(response.s == 1)
                {
                    this.getAllPaymentMethods()
                }
            })
        },
        getAllPaymentMethods: function () {
            this.UserSupport.getAllPaymentMethods({}, (response) => {
                if (response.s == 1) {
                    this.catalogPaymentMethodsAux = response.catalogPaymentMethods
                    this.catalogPaymentMethods = this.catalogPaymentMethodsAux
                }
            })
        },
    },
    mounted() {
        this.UserSupport = new UserSupport
        this.getAllPaymentMethods()
    },
}).mount('#app')
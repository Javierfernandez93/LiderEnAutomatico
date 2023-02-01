import { User } from '../../src/js/user.module.js?t=4.1.3'   

const ProfitViewer = {
    name : 'profit-viewer',
    props : ['user'],
    emits : [],
    data() {
        return {
            User : new User,
            gainStats : {
                investment : {
                    total: 0,
                    percentaje : 0
                },
                referral : {
                    total : 0,
                    percentaje : 0
                },
                balance : 0,
                newReferral : 0,
                totalReferral : 0,
            }
        }
    },
    watch : {
        
    },
    methods: {
        getProfitStats : function() {
            this.User.getProfitStats({},(response)=>{
                if(response.s == 1)
                {
                    Object.assign(this.gainStats,response.gainStats)
                }
            })
        },
    },
    updated() {
    },
    mounted() 
    {   
        this.getProfitStats()
    },
    template : `
        <div class="card bg-gradient-primary text-white mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div
                        v-if="user" 
                        class="col-xl-4 mb-3 mb-xl-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar bg-white">
                                    <img :src="user.image">
                                </div>
                            </div>
                            <div class="col">
                                <div class="fw-semibold">Hola {{user.names}},</div>
                                <div class="fs-5">¡Bienvenido de nuevo!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 align-items-center">    
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                                <div class="card bg-dark">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize text-secondary">Billetera electrónica</p>
                                                    <h5 class="mb-0 text-white fw-semibold">
                                                        $ {{gainStats.balance.numberFormat(2)}}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                    <i class="bi bi-wallet text-lg text-white opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                                <div class="card bg-dark">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize text-secondary">Total de ganancias</p>
                                                    <h5 class="mb-0 text-white fw-semibold">
                                                        $ {{gainStats.referral.total.numberFormat(2)}}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-xl-0">
                                <div class="card bg-dark">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize text-secondary">Retiros</p>
                                                    <h5 class="mb-0 text-white fw-semibold">
                                                        {{gainStats.totalReferral}}
                                                        <span class="d-none text-success text-sm ">+5%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                    <i class="bi bi-wallet text-lg text-white opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
}

export { ProfitViewer } 
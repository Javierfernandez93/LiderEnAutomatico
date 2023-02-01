import { User } from './user.module.js?t=4.1.3'

const ReferralsViewer = {
    name : 'referrals-viewer',
    data() {
        return {
            User: new User,
            levels: null,
            levelsAux: null,
            workingDays: 0,
            query: null,
            viewLevels: 1,
            totals: {
                totalEstimatedGains: 0,
                total_capital: 0
            }
        }
    },
    watch: {
        query: {
            handler() {
                this.filterData()
            },
            deep : true
        }
    },
    methods: {
        filterData() {
            this.levels = this.levelsAux
        
            this.levels = this.levels.map((_users) => {
                const users = _users.filter((referral) => {
                    return referral.names.toLowerCase().includes(this.query.toLowerCase()) || referral.user_login_id.toString().includes(this.query.toLowerCase()) || referral.email.toString().includes(this.query.toLowerCase()) 
                })

                return users.length ? users : []
            }).filter(level => level)

            this.calculateProfit()
            this.getTotals()
        },
        calculateProfit() {
            this.levels.map((users)=>{
                users.map((user)=>{
                    user.estimatedGain = ((parseFloat(user.plan.additional_profit) + parseFloat(user.plan.profit) / this.workingDays) / 100) * user.plan.ammount

                    return user
                })

                return users
            })
        },
        getTotals() {
            this.levels.map((users)=>{
                users.total_capital = 0
                users.totalEstimatedGains = 0

                users.map((user)=>{
                    users.total_capital += user.plan ? parseFloat(user.plan.ammount) : 0
                    users.totalEstimatedGains += user.plan ? parseFloat(user.estimatedGain) : 0

                    return user
                })

                return users
            })
        },
        getReferralsAux(viewLevels,company_id,append) {
            this.getReferrals(viewLevels,company_id).then((response)=>{

                if(append)
                {
                    this.levels = this.levels.concat(response.levels)
                    this.levelsAux = this.levels
                    
                } else {
                    this.levels = response.levels
                    this.levelsAux = response.levels
                }
    
                this.workingDays = response.workingDays
    
                this.calculateProfit()
                this.getTotals()
            }).catch(() => {
                alertMesage("No tenemos más niveles")
            })
        },
        getReferrals(viewLevels,company_id) {
            return new Promise((resolve,reject) => {
                this.User.getReferrals({viewLevels:viewLevels,company_id:company_id}, (response) => {
                    if (response.s == 1) {
                        resolve(response)
                    } else if (response.r == "NOT_DATA") {
                        reject()
                    }
                })
            })
        }
    },
    mounted() {
        this.getReferralsAux(this.viewLevels)
    },
    template : `
        <div v-if="levelsAux" class="container">
            <div class="card shadow-lg overflow-auto mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <input v-model="query" :autofocus="true" type="text" class="form-control" placeholder="Buscar usuario por nombre o ID...">
                        </div>
                        <div class="col-auto">
                            <button @click="getReferralsAux(-1)" class="btn btn-primary">Ver red completa</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <div v-for="(level,index) in levels" class="card shadow-xl mb-4">
                <div class="card-header pb-0">
                    <div class="row align-items-center">
                        <div class="col-auto"><i class="bi bi-people-fill"></i></div>
                        <div class="col fw-semibold text-dark">
                            <div class="small">Afiliados en nivel {{index+1}}</div>
                            <div>Nivel {{index+1}}</div>
                        </div>
                        <div v-if="level" class="col-auto text-end">
                            <div><span class="badge text-secondary">Total de afiliados en éste nível {{level.length}}</span></div>
                        </div>
                    </div>
                </div>

                <div v-if="level" class="card-body px-0 pt-0 pb-2">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-borderless align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuario</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invitado por</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Monto invertido</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ganancia estimada</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Miembro desde</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="referral in level">
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs text-secondary mb-0">{{referral.user_login_id}}</p>
                                        </td>
                                        <td class="cursor-pointer d-flex mb-0 rounded-0 text-start btn bg-light" @click="getReferralsAux(1,referral.user_login_id,true)">
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="referral.image" class="avatar avatar-sm me-3" :alt="referral.names">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{referral.names}}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{referral.email}}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="referral.referral.image" class="avatar avatar-sm me-3" :alt="referral.referral.names">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{referral.referral.names}}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{referral.referral.email}}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span v-if="referral.plan"  
                                                class="badge badge-sm bg-primary small">
                                                $ {{referral.plan.ammount.numberFormat(2)}}
                                            </span>
                                            <span v-else>
                                                Sin monto
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span v-if="referral.plan"  
                                                class="badge badge-sm bg-primary small">
                                                $ {{referral.estimatedGain.numberFormat(2)}}
                                            </span>
                                            <span v-else>
                                                Sin monto
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs text-secondary mb-0">{{referral.signup_date.formatFullDate()}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="level.total_capital">
                                    <tr class="">
                                        <td class="border-0"></td>
                                        <td class="border-0 align-middle text-center">
                                            <h6>Total</h6>
                                            
                                        </td>
                                        <td class="border-0 align-middle text-center">
                                            <h6>$ {{level.total_capital.numberFormat(2)}}</h6>
                                        </td>
                                        <td class="border-0 align-middle text-center">
                                            <h6>$ {{level.totalEstimatedGains.numberFormat(2)}}</h6>
                                        </td>
                                        <td class="border-0"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-secondary text-white text-center">
                <div>No tienes afiliados, por favor comparte tu link personalizado para hacer crecer tu grupo de afiliados</div>
                <div class="fw-semibold fs-5">Puedes encontrar tu Link personalizado en tu <a class="text-white" href="../../apps/backoffice"><u>oficina virtual</u></a></div>
            </div>
        </div>
    `
}

export { ReferralsViewer }
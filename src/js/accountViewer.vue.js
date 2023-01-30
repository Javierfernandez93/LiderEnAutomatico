import { User } from '../../src/js/user.module.js?t=4.1.2'   

const AccountViewer = {
    name : 'account-viewer',
    props: ['user'],
    data() {
        return {
            User : new User,
            landing: null,
            catalogPlans : null,
            catalog_plan_id : null,
            referrals : {
                amount: 0,
                actives: 0
            },
            nextRange : null,
            myRange : null,
        }
    },
    methods: {
        setMyRange : function() {
            this.catalogPlans.map((plan)=>{
                if(plan.catalog_plan_id == this.catalog_plan_id)
                {
                    this.myRange = plan
                }
            })
        },
        setNextRange : function() {
            const next_catalog_plan_id = this.catalog_plan_id+1

            this.catalogPlans.map((plan)=>{
                if(plan.catalog_plan_id == next_catalog_plan_id)
                {
                    this.nextRange = plan
                }
            })
        },
        getMyRange : function() {
            this.User.getMyRange({},(response)=>{
                if(response.s == 1)
                {
                    this.catalogPlans = response.catalogPlans
                    this.catalog_plan_id = response.catalog_plan_id
                    this.setMyRange()
                    this.setNextRange()
                }
            })
        },
        getReferralsShortData : function() {
            this.User.getReferralsShortData({},(response)=>{
                if(response.s == 1)
                {
                    this.referrals = response.referrals
                }
            })
        },
    },
    mounted() 
    {
        this.getMyRange()
        this.getReferralsShortData()
    },
    template : `
        <div class="row align-items-center">
            <div class="col-12 col-xl-6">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <span class="badge bg-dark"><i class="bi bi-diamond-fill"></i> </span>
                            </div>
                            <div class="col">
                                Estado de la cuenta
                            </div>  
                            <div class="col-auto">
                                <span v-if="catalog_plan_id" class="badge bg-success">Activa</span>
                                <span v-else class="badge bg-secondary">Inactiva</span>
                            </div>  
                        </div>
                    </div>
                    <ul v-if="user" class="list-group list-group-flush">
                        <li class="list-group-item bg-secondary">
                            <div class="row">
                                <div class="col">
                                    IP de la ultima conexión
                                </div>
                                <div class="col-auto fw-semibold">
                                    {{user.ip_user_address}}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item bg-secondary">
                            <div class="row">
                                <div class="col">
                                    Ultimo ingreso
                                </div>
                                <div class="col-auto fw-semibold">
                                    {{user.last_login_date.timeSince()}}
                                </div>
                            </div>
                        </li>
                        <li v-if="user.referral" class="list-group-item  bg-secondary">
                            <div class="row">
                                <div class="col">
                                    Patrocinador
                                </div>
                                <div class="col-auto fw-semibold">
                                    <span class="badge bg-primary">
                                        {{user.referral.names}}
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div v-if="referrals" class="row">
                    <div class="col-12 mb-2">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize text-white">Invitados</p>
                                            <h5 class="mb-0 text-white fw-semibold">
                                            {{referrals.amount}}
                                                <span class="d-none text-success text-sm ">+5%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-light shadow text-center border-radius-md">
                                            <i class="bi bi-diamond-half text-lg text-primary opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize text-white">Invitados activos</p>
                                            <h5 class="mb-0 text-white fw-semibold">
                                                {{referrals.actives}}
                                                <span class="d-none text-success text-sm ">+5%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-light shadow text-center border-radius-md">
                                            <i class="bi bi-diamond-half text-lg text-primary opacity-10" aria-hidden="true"></i>
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

export { AccountViewer } 
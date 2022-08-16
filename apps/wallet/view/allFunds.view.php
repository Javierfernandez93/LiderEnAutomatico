<div class="container-fluid py-4" id="app">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card mb-4 shadow-md">
                <div class="card-header pb-0">
                    <div class="row align-items-center">
                        <div class="col-auto"><i class="bi bi-credit-card-2-back-fill"></i></div>
                        <div class="col fw-semibold text-dark">
                            <div class="small">Fondeos</div>
                        </div>
                        <div class="col-auto text-end">
                            <div><a href="../../apps/wallet/addFunds" type="button" class="btn btn-success btn-sm">Añadir fondeo</a></div>
                            <div><span class="badge bg-secondary">Total de fondeos {{transactions.length}}</span></div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <input v-model="query" :autofocus="true" type="text" class="form-control" placeholder="Buscar...">
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div v-if="transactions.length > 0">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Método</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Monto</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fee</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total a pagar</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de creación</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estatus</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="transaction in transactions">
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs text-secondary mb-0">{{transaction.transaction_requirement_per_user_id}}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            {{transaction.catalogPaymentMethod.code}}
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            $ {{transaction.ammount.numberFormat(2)}} <sup>{{transaction.catalogPaymentMethod.currency}}</sup>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            $ {{transaction.fee.numberFormat(2)}} <sup>{{transaction.catalogPaymentMethod.currency}}</sup>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            $ {{transaction.total.numberFormat(2)}} <sup>{{transaction.catalogPaymentMethod.currency}}</sup>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            {{transaction.create_date.formatFullDate()}}
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span v-if="transaction.status == 0" class="badge bg-gradient-danger">
                                                Expirada
                                            </span>
                                            <span v-else-if="transaction.status == 1" class="badge bg-secondary">
                                                Pendiente
                                            </span>
                                            <span v-else-if="transaction.status == 2" class="badge bg-gradient-success">
                                                Aplicada
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <div v-if="transaction.checkout_data.link && transaction.status == 1">
                                                <a class="btn btn-primary m-0" target="_blank" :href="transaction.checkout_data.link">Pagar</a>
                                            </div>
                                            <div v-else>
                                                -
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-else>
                        <div class="alert alert-light text-center mx-3">
                            <div>No tienes fondeos, por favor realiza tu primer fondeo dando <u><a href="../../apps/wallet/addFunds">clic aquí</a></u></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4" id="app">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div v-if="transaction" class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col-2">
                                    <img :src="transaction.catalog_payment_method.image" class="img-fluid">
                                </div>
                                <div class="col">
                                    <div>Paga por {{transaction.catalog_payment_method.description}}</div>
                                </div>

                                <div class="col-auto">
                                    <div>{{transaction.catalog_payment_method.currency}}</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <span class="badge p-0 text-secondary">Número de pago</span>
                                </div>
                                <div class="fw-semibold">
                                    {{transaction.transaction_requirement_per_user_id}}
                                </div>
                            </div>

                            <div v-if="transaction.catalog_payment_method.additional_data">
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <div>
                                            <span class="badge p-0 text-secondary">Banco</span>
                                        </div>
                                        <div class="fw-semibold">
                                            {{transaction.catalog_payment_method.additional_data.bank}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button @click="copy(transaction.catalog_payment_method.additional_data.bank,$event)" class="btn px-3 btn-primary m-0"><i class="bi bi-clipboard"></i></button>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <div>
                                            <span class="badge p-0 text-secondary">Número de cuenta</span>
                                        </div>
                                        <div class="fw-semibold">
                                            {{transaction.catalog_payment_method.additional_data.account}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button @click="copy(transaction.catalog_payment_method.additional_data.account,$event)" class="btn px-3 btn-primary m-0"><i class="bi bi-clipboard"></i></button>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <div>
                                            <span class="badge p-0 text-secondary">CLABE</span>
                                        </div>
                                        <div class="fw-semibold">
                                            {{transaction.catalog_payment_method.additional_data.clabe}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button @click="copy(transaction.catalog_payment_method.additional_data.clabe,$event)" class="btn px-3 btn-primary m-0"><i class="bi bi-clipboard"></i></button>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <div>
                                            <span class="badge p-0 text-secondary">Beneficiario</span>
                                        </div>
                                        <div class="fw-semibold">
                                            {{transaction.catalog_payment_method.additional_data.beneficiary}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button @click="copy(transaction.catalog_payment_method.additional_data.beneficiary,$event)" class="btn px-3 btn-primary m-0"><i class="bi bi-clipboard"></i></button>
                                    </div>
                                </div>

                            </div>

                            <div class="mb-3">
                                <div>
                                    <span class="badge p-0 text-secondary">Sub total</span>
                                </div>
                                <div class="fw-semibold">
                                    $ {{transaction.ammount.numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup>
                                </div>
                            </div>
                            <div v-if="transaction.fee > 0" class="mb-3">
                                <div>
                                    <span class="badge p-0 text-secondary">Tarifa de transacción</span>
                                </div>
                                <div class="fw-semibold">
                                    $ {{transaction.fee.numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup> ({{transaction.catalog_payment_method.fee.numberFormat(2)}} %)
                                </div>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <span class="badge p-0 text-secondary">Monto a pagar</span>
                                </div>
                                <div class="fw-semibold">
                                    $ {{(transaction.ammount).numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-lg">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button text-primary fw-semibold text-decoration-underline" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Instrucciones para depósito
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body text-dark">
                                        <ol class="list-group list-group-numbered list-group-flush">
                                            <li class="list-group-item">Ve a cualquier <b>banco</b></li>
                                            <li class="list-group-item">Lleva los <b>datos de la ficha</b> y realiza un depósito en <b>ventanilla</b></li>
                                            <li class="list-group-item"><b>Aseguráte</b> que los datos sean <b>correctos</b> y que te den un <b>recibo</b> al realizar el depósito por <b>$ {{transaction.ammount.numberFormat(2)}} {{transaction.catalog_payment_method.currency}}</b></li>
                                            <li class="list-group-item"><b>Registra tu pago</b> <u><a class="text-primary fw-semibold" href="../../apps/wallet/allFunds">aquí</a></u></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed text-primary fw-semibold text-decoration-underline" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Instrucciones para transferencia
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body text-dark">
                                        <ol class="list-group list-group-numbered list-group-flush">
                                            <li class="list-group-item">En tu <b>banca por internet</b> realiza un pago a terceros</li>
                                            <li class="list-group-item">Copia el <b>número cuenta, CLABE, Banco y beneficiario</b> de los datos de la ficha</li>
                                            <li class="list-group-item">Pegalos y asegurate que sean <b>correctos</b></li>
                                            <li class="list-group-item">Ingresa la cantidad de <b>$ {{transaction.ammount.numberFormat(2)}} {{transaction.catalog_payment_method.currency}}</b></li>
                                            <li class="list-group-item">Realiza la <b>transferencia</b></li>
                                            <li class="list-group-item"><b>Registra tu pago</b> <u><a class="text-primary fw-semibold" href="../../apps/wallet/allFunds">aquí</a></u></li>
                                        </ol>
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
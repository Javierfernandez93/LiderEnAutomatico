<div class="container-fluid py-4" id="app">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="row">
                <div class="col-lg-7">
                    <div v-if="!transaction.checkoutData">
                        <div class="card mb-3">
                            <div class="card-header pb-0 px-3 pb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="mb-0">
                                            Selecciona tu forma de depósito
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <ul class="list-group list-group-flush">
                                    <li 
                                        v-for="catalogPaymentMethod in catalogPaymentMethods"
                                        :class="transaction.catalog_payment_method.catalog_payment_method_id == catalogPaymentMethod.catalog_payment_method_id ? 'active': ''"
                                        class="list-group-item cursor-pointer list-group-item-action"
                                        @click="setPaymentMethod(catalogPaymentMethod)">
                                        <div class="row align-items-center">
                                            <div class="col-2">
                                                <img :src="catalogPaymentMethod.image" alt="catalogPaymentMethod.currency" class="img-fluid p-3">
                                            </div>
                                            <div class="col">
                                                <div>
                                                    {{catalogPaymentMethod.description}} 
                                                    <span 
                                                        :class="transaction.catalog_payment_method_id == catalogPaymentMethod.catalog_payment_method_id ? 'text-white': ''"
                                                        class="badge text-dark p-0">{{catalogPaymentMethod.currency}}</span> 
                                                </div>

                                                <div>
                                                    <span v-if="catalogPaymentMethod.fee > 0"
                                                        class="badge bg-danger">
                                                        Tarifa de transacción del {{catalogPaymentMethod.fee.numberFormat()}} %
                                                    </span>
                                                    <span v-else
                                                        class="badge bg-success">
                                                        Sin cargos
                                                    </span>
                                                </div>

                                                <div class="text-sm py-2">{{catalogPaymentMethod.additional_info}}</div>
                                            </div>
                                            <div class="col-auto">
                                                <span v-if="catalogPaymentMethod.recomended"
                                                    class="badge bg-light text-primary">Recomendado</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header pb-0 px-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="mb-0">Añadir fondos</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <label>Monto a fondear</label>
                                    <div class="input-group mb-3">
                                        <input 
                                            :disabled="!transaction.catalog_payment_method.catalog_payment_method_id"
                                            :autofocus="true"
                                            v-model="transaction.ammount"
                                            type="text" class="form-control" placeholder="0.0" />
                                    </div>


                                    <button
                                        :disabled="!transaction.ammount || loading" 
                                        @click="createTransactionRequirement" class="btn btn-primary">

                                        <span v-if="!loading">
                                            Añadir fondos
                                        </span>
                                        <span v-else>
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only"></span>
                                            </div>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div v-else
                        class="card">
                        <div class="card-body">
                            <div v-if="transaction.catalog_payment_method.catalog_currency_id == 1">
                                <div class="alert alert-info text-white text-center">
                                    <strong>Aviso</strong>
                                    Haz clic en "IR A PAGAR" y sigue las instrucciones para fondear tu cuenta.
                                </div>
                                <div class="mb-3">
                                    <span class="text-xs">
                                        Cantidad (BTC)
                                    </span>
                                    <h6 class="mb-1 text-dark text-sm">
                                        {{ transaction.checkoutData.amount }} BTC
                                    </h6>
                                </div>

                                <div class="mb-3">
                                    <span class="text-xs">
                                        Dirección (BTC)
                                    </span>
                                    <h6 class="mb-1 text-dark text-sm">
                                        {{ transaction.checkoutData.address }} 
                                    </h6>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="text-xs">
                                        Código QR
                                    </span>
                                    <h6 class="mb-1 text-dark text-sm">
                                        <img class="img-fluid" :src="transaction.checkoutData.qrcode_url" />
                                    </h6>
                                </div>
                                    
                                <div class="card-footer">
                                    <h6 class="mb-1 text-dark text-sm">
                                        <a target="_blank" class="btn btn-success w-100" :href="transaction.checkoutData.checkout_url">Ir a pagar</a>
                                    </h6>
                                </div>
                            </div>
                            <div v-else-if="transaction.catalog_payment_method.catalog_payment_method_id == CatalogCurrency.PAYPAL">
                                <div class="row align-items-center mb-3">
                                    <div class="col-2">
                                        <img :src="transaction.catalog_payment_method.image" class="img-fluid">
                                    </div>
                                    <div class="col">
                                        <div>Paga por {{transaction.catalog_payment_method.description}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <div>
                                            {{transaction.catalog_payment_method.currency}}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-dark">
                                    <div class="mb-3">
                                        <div>
                                            <span class="badge p-0 text-secondary">Número de pago</span>
                                        </div>
                                        <div class="fw-semibold">
                                            {{transaction.checkoutData.txn_id}}
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
                                    <div v-if="transaction.checkoutData.fee > 0" class="mb-3">
                                        <div>
                                            <span class="badge p-0 text-secondary">Tarifa de transacción</span>
                                        </div>
                                        <div class="fw-semibold">
                                            $ {{transaction.checkoutData.fee.numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup> ({{transaction.catalog_payment_method.fee.numberFormat(2)}} %)
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div>
                                            <span class="badge p-0 text-secondary">Monto a pagar</span>
                                        </div>
                                        <div class="fw-semibold">
                                            $ {{(transaction.checkoutData.total).numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary" target="_blank" :href="transaction.checkoutData.link">Ir a pagar</a>
                            </div>
                            <div v-else-if="transaction.catalog_payment_method.catalog_payment_method_id == CatalogCurrency.STRIPE || transaction.catalog_payment_method.catalog_payment_method_id == CatalogCurrency.STRIPE_USA"> 
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
                                        {{transaction.checkoutData.txn_id}}
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
                                <div v-if="transaction.checkoutData.fee > 0" class="mb-3">
                                    <div>
                                        <span class="badge p-0 text-secondary">Tarifa de transacción</span>
                                    </div>
                                    <div class="fw-semibold">
                                        $ {{transaction.checkoutData.fee.numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup> ({{transaction.catalog_payment_method.fee.numberFormat(2)}} %)
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div>
                                        <span class="badge p-0 text-secondary">Monto a pagar</span>
                                    </div>
                                    <div class="fw-semibold">
                                        $ {{(transaction.checkoutData.total).numberFormat(2)}} <sup>{{transaction.catalog_payment_method.currency}}</sup>
                                    </div>
                                </div>
                                
                                <div v-if="transaction.catalog_payment_method.catalog_payment_method_id == CatalogCurrency.STRIPE" class="alert alert-light">
                                    <strong>Aviso</strong>
                                    Se fondearán apróximadamente <b>${{transaction.checkoutData.ammount_to_add.numberFormat(2)}}</b> USD a tu cuenta
                                </div>
                                
                                <a class="btn btn-primary" target="_blank" :href="transaction.checkoutData.link">Ir a pagar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header pb-0 px-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-0">Últimos fondeos</h6>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end align-items-center">
                                    <button 
                                        @click="viewAllDeposits"
                                        class="btn btn-link m-0">Ver todos</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4 p-3">
                            <div
                                v-if="lastTransactions.length > 0">

                                <ul class="list-group">
                                    <li 
                                        v-for="lastTransaction in lastTransactions"
                                        class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                                            </button>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">
                                                    Fondeo ({{lastTransaction.catalogPaymentMethod.code}}) -
                                                    <span v-if="lastTransaction.status == 1"
                                                        class="text-danger">
                                                        Pendiente
                                                    </span>
                                                    <span v-else-if="lastTransaction.status == 2"
                                                        class="text-success">
                                                        Depósitado
                                                    </span>
                                                </h6>
                                                <span class="text-xs">
                                                    {{lastTransaction.create_date.formatFullDate()}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-danger text-gradient text-sm font-weight-bold">
                                                $ {{lastTransaction.total.numberFormat(2)}}
                                                <span class="badge p-0 text-dark fw-semibold">{{lastTransaction.catalogPaymentMethod.currency}}</span>
                                            </div>

                                            <div v-if="lastTransaction.status == 1 && lastTransaction.checkout_data">
                                                <a :href="lastTransaction.checkout_data.link"
                                                    class="text-primary text-decoration-underline">Ir a pagar</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
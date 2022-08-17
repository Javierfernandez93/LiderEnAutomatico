<div class="container-fluid py-4" id="app">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div v-if="transaction" class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div v-if="status == STATUS.PENDING_FOR_REGISTRATION">
                                <div class="row align-items-center mb-3">
                                    <div class="col-2">
                                        <img :src="transaction.catalog_payment_method.image" class="img-fluid">
                                    </div>
                                    <div class="col">
                                        <div>Registro de {{transaction.catalog_payment_method.description}}</div>
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

                                <div class="form-floating mb-3">
                                    <input 
                                        :class="transaction.payment_reference ? 'is-valid' : ''"
                                        v-model="transaction.payment_reference"
                                        type="text" class="form-control" id="payment_reference" placeholder="Referencia de pago">
                                    <label for="payment_reference">Referencia de pago</label>
                                </div>

                                <div class="mb-3 cursor-pointer overflow-hidden text-center upload-photo fw-semibold text-primary text-decoration-underline" @click="openFileManager">
                                    <input class="form-control d-none" ref="file" id="image" @change="uploadFile($event)" capture="filesystem" type="file" accept=".jpg, .png, .jpeg" />
                                    <div v-if="transaction.image">
                                        <img :src="transaction.image" class="img-fluid"/>
                                    </div>
                                    <div class="p-3">
                                        <span v-if="!transaction.image">
                                            Subir evidencia de pago 
                                        </span>
                                        <span v-else>
                                            Cambiar evidencia de pago 
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="">
                                    <button 
                                        :disabled="!paymentFilled"
                                        @click="registerTransaction(transaction)"
                                        class="btn btn-primary w-100">Guardar referencia</button>
                                </div>
                            </div>
                            <div v-if="status == STATUS.REGISTERED">
                                <div class="text-center">
                                    <div class="fs-4 fw-semibold text-gradient text-primary"><i class="bi bi-bookmark-check-fill"></i></div>
                                    <div class="fs-4 fw-semibold text-gradient text-primary">Pago registrado</div>
                                    <div class="mb-3">Muchas gracias tu pago ha sido registrado</div>
                                    
                                    <div class="alert alert-light">
                                        
                                        <div class="mb-3"><strong>Importante</strong> Por favor, para agilizar tu fondeo envía la fotografía de tu transferencia o depósito en el siguiente <b>WhatsApp</b></div>
                                        <button 
                                            @click="goToWhatsAppSupport(transaction)"
                                            class="btn btn-success btn-lg px-3"><i class="bi bi-whatsapp"></i> Enviar WhatsApp</button>
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
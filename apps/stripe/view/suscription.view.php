<script src="http://js.stripe.com/v3/"></script>

<div id="app">
	<div class="row justify-content-center">
		<div class="col-12 col-xl-4">
			<div class="card shadow-xl">
				<div v-if="loading">
					<div class="card-body">
						<div class="row justify-content-center">
							<div class="col-12 col-xl-4 text-center">
								<div class="spinner-border" role="status">
									<span class="sr-only"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-else>
					<div v-if="paymentStatus == STATUS.PAYMENT_PENDING" class="card-body">
						<div>
							<div class="row align-items-center py-3 border-bottom mb-3">
								<div class="col-auto">
									<div class="fs-5 text-primary">Stripe</div>
									<div class="text-xs text-secondary">Suscripción</div>
								</div>
								<div class="col text-end">
									<div class="text-xs text-secondary">Total</div>
									<div class="fs-5 text-primary">$ {{ammount.numberFormat(2)}} {{currency}}</div>
								</div>
							</div>

							<div v-if="paymentIntervals" class="pb-3">
								<label>Plan de pagos</label>
								<select class="form-select" v-model="recurring.interval" aria-label="Intervalo">
                                    <option v-for="paymentInterval in paymentIntervals" v-bind:value="paymentInterval.interval">
                                        {{ paymentInterval.text }} 
                                    </option>
                                </select>
							</div>

							<div class="pb-3">
								<label>Ingresa los datos de tu tarjeta</label>

								<div id="card-element" class="mt-3">
								</div>
							</div>

							<div v-if="error">
								<div class="alert alert-danger text-white alert-dismissable" role="alert">
									{{error.message}}
								</div>
							</div>

							<div class="mt-3">
								<button @click="makePayment" class="btn btn-success w-100">
									<span v-if="loadingButton == false">
										Pagar ahora
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
					<div v-else-if="paymentStatus == STATUS.PAYMENT_DONE">
						<div class="text-center">
							<div class="fs-4 fw-semibold text-gradient text-primary"><i class="bi bi-bookmark-check-fill"></i></div>
							<div class="fs-4 fw-semibold text-gradient text-primary">Pago aprovado</div>
							<div class="badge p-0 text-dark">Muchas gracias tu pago ha sido aprobado</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
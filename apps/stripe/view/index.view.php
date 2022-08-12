<script src="http://js.stripe.com/v3/"></script>

<div id="app">
	<div class="row justify-content-center">
		<div class="col-12 col-xl-4">
			<div class="card">
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
					<div class="card-body text-center">
						<div v-if="paymentStatus == STATUS.PAYMENT_PENDING">
							<div class="pb-3">
								<div>Ingresa los datos</div>
								<div>tu tarjeta de débito o crédito</div>
							</div>

							<div id="card-element">
							</div>

							<div v-if="error">
								<div class="alert alert-danger alert-dismissable" role="alert">
									{{error.message}}
								</div>
							</div>

							<div v-if="ammount" class="mt-3">
								<div class="mb-3 text-center">
									<div>
										<span class="badge p-0 text-secondary">Monto a pagar</span>
									</div>
									<div class="fw-semibold">
										$ {{ammount.numberFormat(2)}}
									</div>
								</div>
								
								<div class="card-footer">
									<button :disabled="error || loadingButton" @click="makePayment" class="btn btn-success">
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
						<div v-else-if="paymentStatus == STATUS.PAYMENT_EXPIRED">
							<div class="text-center">
								<strong>Aviso</strong>
								El pago ya fue procesado anteriormente
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
	</div>
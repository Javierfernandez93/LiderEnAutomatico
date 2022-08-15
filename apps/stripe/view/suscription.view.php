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
					<div class="card-body text-center">
						<div>
							<div class="pb-3">
								<div>Ingresa los datos</div>
								<div>tu tarjeta de débito o crédito</div>
							</div>

							<div v-if="paymentIntervals">
								<select class="form-select" v-model="recurring.interval" aria-label="Intervalo">
                                    <option v-for="paymentInterval in paymentIntervals" v-bind:value="paymentInterval.name">
                                        {{ paymentInterval.text }} - {{ paymentInterval.name }}
                                    </option>
                                </select>
							</div>

							<div v-if="ammount" class="mt-3 fs-4 fw-semibold">
								$ {{ammount.numberFormat(2)}} <sup>{{currency}}</sup>
							</div>

							<div id="card-element" class="mt-3">
							</div>

							<div v-if="error">
								<div class="alert alert-danger alert-dismissable" role="alert">
									{{error.message}}
								</div>
							</div>

							<div class="mt-3">
								
								<div class="card-footer">
									<button @click="makePayment" class="btn btn-success">
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
					</div>
				</div>
			</div>
		</div>
	</div>
<div class="container-fluid py-4" id="app">

    <div class="alert alert-info text-white">
        <div class="row d-flex align-items-center">
            <div class="col">
                <strong>Aviso</strong>
                <div class="fw-semibold fs-6">Ya puedes añadir capital a tu cuenta con coinpayments.net</div>
            </div>
            <div class="col-auto">
                <a href="../../apps/wallet/addFunds" class="btn btn-light">Añadir fondos</a>
            </div>
        </div>
    </div>

    <profit-viewer></profit-viewer>

    <div class="row">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100">
                                <p class="mb-1 pt-2 text-bold">Aviso</p>
                                <h5 class="font-weight-bolder">¿Estás preparado para ganar?</h5>
                                <p class="mb-5">Gran capital te ofrece varios esquemas de ganancias.</p>
                                <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto"
                                    href="../../apps/backoffice/plans">
                                    Leer más
                                    <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                            <div class="bg-gradient-primary border-radius-lg h-100">
                                <img src="../../src/img/shapes/waves-white.svg" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                                
                                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                    <img class="w-100 position-relative z-index-2 pt-4" src="../../src/img/illustrations/rocket-white.png" alt="rocket">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100 p-3 overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('../../src/img/ivancik.jpg');">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder">Haz crecer tu grupo y gana</h5>
                    <p class="text-white">Gran Capital te premia por referir usuarios activos.</p>
                    
                    <div v-if="landing">
                        <div class="mb-3">
                            <div class="text-light">Link personalizado</div>
                            <div>
                                <a class="text-white" :href="landing">{{landing}}</a>
                            </div>
                        </div>

                        <button class="btn btn-primary" ref="landing" :data-text="landing" data-helper="Link de landing copiada" @click="copyLanding">
                            Copiar Landing Page
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <notice-viewer></notice-viewer>
</div>
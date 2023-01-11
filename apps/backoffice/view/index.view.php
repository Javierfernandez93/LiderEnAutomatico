<div class="container-fluid py-4" id="app">
    <div class="alert alert-secondary text-left text-white">
        <div class="row align-items-center">
            <div class="col-12 col-xl">
                <strong>Aviso importante</strong>
                Ya puedes generar tu documento, da click 
            </div>
            <div class="col-12 col-xl-auto">
                <a class="btn btn-primary mb-0" href="../../apps/backoffice/fxwinning">Crea tu documento aquí</a>
            </div>
        </div>
    </div>
    <profit-viewer
        :user="user"></profit-viewer>
    <afiliate-viewer></afiliate-viewer>
    <account-viewer
        :user="user"></account-viewer>
    <notice-viewer></notice-viewer>
</div>
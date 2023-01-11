<div class="row d-flex justify-content-center align-items-center py-5" id="app">
    
    <signup-viewer v-if="step == 1" @setstep="setStep"></signup-viewer>
    
    <div class="d-none">Paso {{step}}</div>
    
    <fxwinning-viewer ref="fxwinning"  :class="step === 2 ? '' :'d-none'"
        :step="step"
        :backoffice="backoffice"
        >
    </fxwinning-viewer>
</div>
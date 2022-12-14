<div class="container-fluid py-4" id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Rangos


                    <div class="row justify-content-center range-box"
                        :class="!catalog_plan_id ? 'range-box-bar-fixed' : ''">
                        <div class="col-12 col-xl-10">
                            <div 
                                v-if="catalogPlans.length > 0"
                                class="row position-relative">

                                <div v-for="catalogPlan in catalogPlans"
                                    class="col-12 col-xl text-center">
                                    <div :class="catalogPlan.catalog_plan_id == catalog_plan_id ? 'actual-range bg-light rounded' : ''"
                                        class="p-4 position-relative">
                                        <div>


                                            <div class="row d-flex justify-content-center mb-3 position-relative range-box-inner bottom-0">
                                                <span 
                                                    :class="catalogPlan.catalog_plan_id == catalog_plan_id ? 'border-primary bg-light' : 'border-secondary bg-white'"
                                                    class="dot border p-1">
                                                    <span 
                                                        :class="catalogPlan.catalog_plan_id == catalog_plan_id ? 'bg-primary' : 'bg-secondary'"
                                                        class="dot-inner">
                                                    </span>
                                                </span>
                                            </div>
                                            <div>
                                                <img :src="catalogPlan.image" class="img-fluid px-3">
                                            </div>
                                            <div>
                                                <div class="fs-4 fw-semibold text-gradient text-primary">
                                                    {{catalogPlan.name}} 
                                                </div>
                                                <div class="">
                                                    <span class="badge bg-gradient-primary">Profit {{catalogPlan.profit.numberFormat(2)}}%</span>
                                                    <span class="badge bg-gradient-success">
                                                        $ {{catalogPlan.additional_data.min.numberFormat(2)}} - 
                                                        <span v-if="catalogPlan.additional_data.max == -1">
                                                            en adelante
                                                        </span>
                                                        <span v-else>
                                                            $ {{catalogPlan.additional_data.max.numberFormat(2)}}
                                                        </span>
                                                    </span>
                                                    <div v-if="catalogPlan.description_list">
                                                        <span v-for="list in catalogPlan.description_list" class="badge text-primary border mx-1 my-1 border-primary">
                                                            {{list}}
                                                        </span>
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
    
            </div>
        </div>
    </div>
</div>
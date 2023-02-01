import { User } from '../../src/js/user.module.js?t=4.1.3'   

const ToolsViewer = {
    name : 'tools-viewer',
    props : [],
    emits : [],
    data() {
        return {
            User : new User,
            tools : null
        }
    },
    watch : {
        pops: {
            handler() {
                this.showPop = true
            },
            deep: true
        },
    },
    methods: {
        nextPop : function(index) {
            const max = this.pops.length - 1
            
            this.pops[index].view = false
            
            if(index < max)
            {
                this.pops[index+1].view = true
            } else {
                $(this.$refs.viewerModal).modal('hide')
            }
        },
        setFirstPopAsView : function() {
            this.pops[0].view = true
        },
        getToolsList : function() {
            this.User.getToolsList({},(response)=>{
                if(response.s == 1)
                {
                    this.tools = response.tools
                }
            })
        }
    },
    updated() {
    },
    mounted() 
    {   
        this.getToolsList()
    },
    template : `
        <div v-if="tools" class="row">
            <div v-for="tool in tools" class="col-12 col-xl-4">
                <div class="card shadow-xl overflow-hidden">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto">
                                <span class="badge bg-primary"><i class="bi bi-tools"></i></span>
                            </div>
                            <div class="col fw-semibold">
                                {{tool.title}}
                            </div>
                        </div>
                    </div>
                    <div v-if="tool.route" class="tool-body"> 
                        <div v-if="tool.route.isImage()">
                            <img :src="tool.route" class="img-fluid"> 
                        </div>
                        <div v-else-if="tool.route.isFile()" class="row d-flex align-items-center justify-content-center text-center h-100">
                            <div class="col-12">
                                <i class="bi bi-cloud fs-1"></i>
                                <div>Archivo</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="tool.description" class="card-footer">
                        <div v-html="tool.description">

                        </div>
                    </div>

                    <div>
                        <a class="btn btn-primary btn-lg rounded-0 w-50 m-0" :href="tool.route" download>Descargar</a>
                        <a class="btn btn-primary btn-lg rounded-0 w-50 m-0" :href="tool.route">Visualizar</a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-light text-center">
                Aún no tenemos herramientas. Vuelve más tarde
            </div>
        </div>
    `,
}

export { ToolsViewer } 
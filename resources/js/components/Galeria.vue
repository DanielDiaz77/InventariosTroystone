<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="/">Escritorio</a> </li>
    </ol>
    <div class="container-fluid p-1">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header" style="background-color:#fff;">
                <div class="form-inline">
                    <div class="form-group ml-2">
                        <div class="input-group">
                            <select class="form-control mb-1 rounded" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                <option value="lote">Lote</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarProject(1,buscar,criterio,estadoProj,entregaProj)"
                                class="form-control mb-1 rounded" placeholder="Texto a buscar...">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <h2>En construcción <i class="fa fa-cogs" aria-hidden="true"></i></h2>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
  </main>
</template>

<script>
export default {
    data() {
        return {
            galeria_id: 0,
            nombre : '',
            descripcion : '',
            lote : '',
            cover : '',
            fecha_hora : '',
            area: '',
            estado : '',
            pagination : {
                'total'        : 0,
                'current_page' : 0,
                'per_page'     : 0,
                'last_page'    : 0,
                'from'         : 0,
                'to'           : 0,
            },
            offset : 3,
            criterio : 'nombre',
            buscar : '',
            areaGal : '',
            errorGaleria: 0,
            errorMostrarMsjGaleria: [],
            arrayGalerias : [],
            arrayLinks : [],
            arrayImagenes : []

        };
    },

    computed:{
    },
    methods: {
    },
    mounted() {
        console.log(`Component Gallery Mounted`);
    }
};
</script>

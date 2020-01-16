<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Escritorio</a> </li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Tareas
                <button type="button" @click="newTask()" class="btn btn-secondary" v-if="!OpenDet">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <template v-if="listado == 0">
                <div class="card-body">
                    <div class="form-inline">
                        <div class="form-group mb-2 col-12">
                            <div class="input-group">
                                <select class="form-control mb-1" v-model="criterio">
                                    <option value="title">Actividad</option>
                                    <option value="emisor">Emisor</option>
                                    <option value="receptor">Receptor</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarActividades(1,buscar,criterio,estado)" class="form-control mb-1" placeholder="Texto a buscar">
                            </div>
                            <div class="input-group">
                            <select class="form-control mb-1" v-model="estado" @change="listarActividades(1,buscar,criterio,estado)">
                                <option value="0">Incompleta</option>
                                <option value="1">Completada</option>
                                <option value="2">Cancelada</option>
                            </select>
                            <button type="submit" @click="listarActividades(1,buscar,criterio,estado)" class="btn btn-primary mb-1"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm table-hover">
                            <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Emisor</th>
                                <th>Actividad</th>
                                <th>Detalles</th>
                                <th>Fecha Compromiso</th>
                                <th>Receptor</th>
                                <th>Completado</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody v-if="arrayActividad.length">
                                <tr v-for="actividad in arrayActividad" :key="actividad.id">
                                    <td>
                                        <div class="form-inline">
                                            <button type="button" @click="verActividad(actividad)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="actividad.status == 0">
                                                <button type="button" @click="editTask(actividad)" class="btn btn-warning btn-sm">
                                                    <i class="icon-pencil"></i>
                                                </button> &nbsp;
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarActividad(actividad.id)">
                                                    <i class="icon-trash"></i>
                                                </button>&nbsp;
                                            </template>
                                        </div>
                                    </td>
                                    <td v-text="actividad.emisor"></td>
                                    <td v-text="actividad.title"></td>
                                    <td v-text="actividad.content"></td>
                                    <td> {{ convertDate(actividad.end) }} </td>
                                    <td v-text="actividad.receptor"></td>
                                    <td class="text-center">
                                        <input type="checkbox" :id="'chk'+actividad.id" v-model="actividad.status"
                                            @change="cambiarEstado(actividad.id,actividad.status)" v-if="actividad.status != 2">
                                        <template v-if="actividad.status == 1">
                                            <label :for="'chk'+actividad.id">Completada</label>
                                        </template>
                                        <template v-else-if="actividad.status == 0">
                                            <label :for="'chk'+actividad.id">Incompleta</label>
                                        </template>
                                        <template v-else-if="actividad.status == 2">
                                            <span class="badge badge-danger">Cancelada</span>
                                        </template>
                                    </td>
                                    <td>
                                        <div v-if="actividad.status == 0">
                                            <span class="badge badge-warning">Incompleta</span>
                                        </div>
                                        <div v-else-if="actividad.status == 1">
                                            <span class="badge badge-success">Completada</span>
                                        </div>
                                        <div v-else-if="actividad.status == 2">
                                            <span class="badge badge-danger">Cancelada</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <strong>NO hay actividades registradas o con ese criterio...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio,estado)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio,estado)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, criterio,estado)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </template>

            <template v-if="listado == 1">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-12 text-center">
                           <h3 v-text="tituloDetalle"></h3>
                        </div>&nbsp;
                    </div>
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row d-flex justify-content-center">
                            <div class="input-group input-group-sm col-12 col-md-12 col-lg-4 col-xl-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Receptor</span>
                                </div>
                                <select class="form-control" v-model="idreceptor">
                                    <option value="0" disabled>Seleccione un receptor</option>
                                    <option v-for="receptor in arrayReceptores" :key="receptor.id" :value="receptor.id" v-text="receptor.nombre"></option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-5 col-xl-3 col-md-12 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Fecha Compromiso </span>
                                </div>
                                <date-picker name="date" v-model="end" :config="options"></date-picker>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="input-group input-group-sm col-12 col-lg-9 col-xl-6  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Titulo</span>
                                </div>
                                <input type="text" v-model="title" class="form-control" placeholder="Título de la actividad"/>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="input-group input-group-sm col-12 col-lg-9 col-xl-6 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Detalles</span>
                                </div>
                                <textarea class="form-control rounded-0" style="resize: none;" rows="3" maxlength="256" v-model="content"></textarea>
                            </div> <br>
                        </div>
                        <div v-show="errorActividad" class="form-group row div-error d-flex justify-content-center">
                            <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjActividad" :key="error" v-text="error"></div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-lg-6 mb-3">
                                <button type="button" @click="cerrarDetalle()"  class="btn btn-secondary float-right">Cancelar</button>
                                <button v-if="TaskNew" type="button" class="btn btn-primary float-right mr-2" @click="registrarActividad()">Guardar</button>
                                <button v-if="TaskEdit" type="button" class="btn btn-primary float-right mr-2" @click="actualizarActividad()">Actualizar</button>
                            </div>
                        </div>
                    </form>
                    <br><br><br><br><br>
                </div>
            </template>

        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content content-category">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-right mb-0 mb-md-2">
                            <template v-if="status == 0">
                                <span style="font-size: 20px;" class="badge badge-warning">
                                    Incompleta <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                </span>
                            </template>
                            <template v-else-if="status == 1">
                                <span style="font-size: 20px;" class="badge badge-success">
                                    Completada <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                </span>
                            </template>
                            <template v-else-if="status == 2">
                                <span style="font-size: 20px;" class="badge badge-danger">
                                    Cancelada <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                </span>
                            </template>
                        </div>
                        <div class="col-12 text-center">
                            <h3 v-text="title"></h3>
                        </div>
                        <div class="col-12 text-center">
                            <h5 v-text="content"></h5>
                        </div>
                        <div class="col mt-3">
                            <span style="font-size: 15px;"><strong>Fecha compromiso: </strong> {{ convertDate(end)}}</span>
                        </div>

                        <div class="col mt-3">
                            <span style="font-size: 15px;"><strong>Creado por: </strong> {{ nom_emisor }} el dia {{ convertDate(start) }}</span>
                        </div>
                    </div>
                    <div class="modal-footer d-block d-sm-block d-md-none">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import moment from 'moment';
import datePicker from 'vue-bootstrap-datetimepicker';
Vue.use(datePicker);
export default {
    data() {
        return {
            activity_id : 0,
            title : "",
            content : "",
            start : "",
            end : "",
            status : 0,
            idemisor : 0,
            idreceptor : 0,
            arrayActividad: [],
            errorActividad: 0,
            errorMostrarMsjActividad: [],
            arrayReceptores : [],

            pagination : {
                'total'        : 0,
                'current_page' : 0,
                'per_page'     : 0,
                'last_page'    : 0,
                'from'         : 0,
                'to'           : 0,
            },
            offset : 3,
            criterio : 'title',
            buscar : '',
            estado : 0,
            listado : 0,
            tituloDetalle : "",
            OpenDet : false,
            TaskEdit : false,
            TaskNew : false,
            options: {
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: false,
                showClear: true,
                showClose: true,
                daysOfWeekDisabled: [0],
                minDate: moment().subtract(120, 'minutes'),
                maxDate: moment().add(7, 'days'),
            },
            modal : 0,
            tituloModal : "",
            nom_receptor : "",
            nom_emisor : ""
        };
    },
    computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
    methods: {
        listarActividades (page,buscar,criterio,estado){
            let me=this;
            var url= '/actividad?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&estado='+ estado;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayActividad = respuesta.actividades.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cambiarPagina(page,buscar,criterio,estado){
            let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarActividades(page,buscar,criterio,estado);
        },
        registrarActividad() {
            if (this.validarActividad()) {
                return;
            }

            let me = this;

            axios.post("/actividad/registrar", {
                title: this.title,
                content: this.content,
                end : this.end,
                idreceptor : this.idreceptor
            })
            .then(function(response) {
                me.cerrarDetalle();
                swal.fire(
                'Atención!',
                'La actividad ha sido registrada con éxito.',
                'success')
                me.listarActividades(1,'','nombre','0');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        actualizarActividad() {
            if (this.validarActividad()) {
                return;
            }
            let me = this;
            axios.put("/actividad/actualizar", {
                id : this.activity_id,
                start : this.start,
                end : this.end,
                title : this.title,
                content : this.content,
                status : this.status,
                idreceptor : this.idreceptor,
                idemisor : this.idemisor
            })
            .then(function(response) {
                me.cerrarDetalle();
                swal.fire(
                'Atención!',
                'La actividad ha sido actualizada con éxito.',
                'success')
                me.listarActividades(1,'','nombre','0');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        desactivarActividad(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de cancelar esta actividad?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/actividad/desactivar', {
                        'id' : id
                    }).then(function(response) {
                        me.listarActividades(1,'','nombre','0');
                        swalWithBootstrapButtons.fire(
                            "Desactivado!",
                            "La actividad ha sido cancelada con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        validarActividad() {
            this.errorActividad = 0;
            this.errorMostrarMsjActividad = [];

            if (!this.title) this.errorMostrarMsjActividad.push("El titulo de la actividad no puede estar vacío.");
            if (!this.idreceptor) this.errorMostrarMsjActividad.push("Seleccione un usuario para asignar la actividad.");
            if (!this.content) this.errorMostrarMsjActividad.push("El contenido de la actividad no puede estar vacio");
            if (!this.end) this.errorMostrarMsjActividad.push("Seleccione una fecha compromiso para la actividad.");

            if (this.errorMostrarMsjActividad.length) this.errorActividad = 1;

            return this.errorActividad;
        },
        newTask(){
            this.listado = 1;
            this.title = "";
            this.content = "";
            this.idreceptor = 0;
            this.end = "";
            this.OpenDet = true;
            this.TaskNew = true;
            this.TaskEdit = false;
            this.tituloDetalle = 'Crear Nueva Actividad';
            this.selectReceptor();
        },
        editTask(data = []){
            this.listado = 1;
            this.activity_id = data['id'];
            this.start = data['start'];
            this.end = data['end'];
            this.title = data['title'];
            this.content = data['content'];
            this.status = data['status'];
            this.idreceptor = data['idreceptor'];
            this.idemisor = data['idemisor'];
            this.OpenDet = true;
            this.TaskNew = false;
            this.TaskEdit = true;
            this.tituloDetalle = 'Editar Actividad';
            this.selectReceptor();
        },
        cerrarDetalle(){
            this.listado = 0;
            this.activity_id = 0;
            this.start = "";
            this.end = "";
            this.title = "";
            this.content = "";
            this.status = 0;
            this.idreceptor = 0;
            this.idemisor = 0;
            this.tituloDetalle = "";
            this.OpenDet = false;
            this.TaskNew = false;
            this.TaskEdit = false;
            this.arrayReceptores = [];
        },
        selectReceptor(){
            let me=this;
            var url= '/user/selectUsuarioAct';

            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayReceptores = respuesta.usuarios;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cambiarEstado(id,estado){
            let me = this;

            var pageac = me.pagination.current_page;


            if(estado == true){
                me.estado = 1;
            }else{
                me.estado = 0;
            }

            var estadoAct = me.estado;

            axios.put('/actividad/cambiarEstado',{
                'id': id,
                'estado' : this.estado
            }).then(function (response) {
                if(estado == 1){
                    swal.fire(
                    'Completado!',
                    'La actividad ha sido registrado como completada.',
                    'success')
                }else{
                    swal.fire(
                    'Atención!',
                    'La actividad ha sido registrado como incompleta.',
                    'warning')
                }
                me.listarActividades(pageac,'','nombre',estadoAct);
            }).catch(function (error) {
                console.log(error);
            });
        },
        verActividad(data = []){
            this.modal = 1;
            this.start = data['start'];
            this.end = data['end'];
            this.title = data['title'];
            this.content = data['content'];
            this.status = data['status'];
            this.nom_receptor = data['receptor'];
            this.nom_emisor = data['emisor'];
            this.tituloModal = `Actividad para ${ this.nom_receptor }`;
        },
        cerrarModal(){
            this.modal = 0;
            this.start = "";
            this.end = "";
            this.title = "";
            this.content = "";
            this.status = 0;
            this.idreceptor = 0;
            this.idemisor = 0;
            this.tituloDetalle = "";
            this.nom_receptor = "";
            this.nom_emisor = "";
        },
        convertDate(date){
            moment.locale('es');
            let me=this;
            var datec = moment(date).format('DD MMM YYYY hh:mm:ss a');
            return datec;
        },
    },
    mounted() {
        this.listarActividades(1,this.buscar, this.criterio, this.estado);
    }
};
</script>
<style>
    .modal-content {
      width: 100% !important;
      position: absolute !important;
    }
    .mostrar {
      display: list-item !important;
      opacity: 1 !important;
      position: absolute !important;
      background-color: #3c29297a !important;
    }
    .div-error {
      display: flex;
      justify-content: center;
    }
    .text-error {
      color: red !important;
      font-weight: bold;
    }
    .content-category{
        height: 350px !important;
    }
</style>

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
                <i class="fa fa-align-justify"></i> Actividades
                <button type="button" class="btn btn-secondary" @click="abrirModal()">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                 <!-- <vue-cal locale="es" :events="events" selected-date="2018-11-19"></vue-cal> -->
                <vue-cal style="height: 600px" locale="es"
                    :selected-date = getDateToday
                    :min-date = getDateToday
                    :time-from="7 * 60"

                    resize-x
                    default-view="month"
                    :disable-views="['years']"
                    :hide-weekdays="[7]"
                    events-on-month-view="short"
                    :events="events"
                    :on-event-click="onEventClick">
                </vue-cal>
            </div>
        </div>
    </div>
    <!--Inicio del modal agregar/actualizar actividad-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content content-event">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row m-0">
                            <div class="col-md-5 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Cliente (*)</strong></label>
                                        <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Seleccionar un cliente..."
                                        :onChange="getDatosCliente">
                                        </v-select>
                                </div>
                            </div>&nbsp;
                            <template v-if="idcliente">

                                <div class="col-md-3 text-center">
                                    <div class="form-group" v-if="cliente">
                                        <label><strong>Cliente</strong></label>
                                        <h4><strong v-text="cliente"></strong></h4>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="form-group" v-if="tipo">
                                        <label for=""><strong>Tipo de cliente</strong></label>
                                        <h6 for=""><strong v-text="tipo"></strong></h6>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="form-group" v-if="rfc">
                                        <label for=""><strong>RFC</strong></label>
                                        <h6 for=""><strong v-text="rfc"></strong></h6>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center sinpadding" v-if="telefono">
                                    <div class="form-group">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <h6 for=""><strong v-text="telefono"></strong></h6>
                                    </div>
                                </div>&nbsp;
                                <div class="col-md-4 text-center sinpadding" v-if="company">
                                    <div class="form-group">
                                        <label for=""><strong>Contacto</strong></label>
                                        <h6 for=""><strong> {{company}} | {{tel_company}}</strong></h6>
                                    </div>
                                </div>&nbsp;
                            </template>
                        </div>
                        <div class="form-group row m-0">
                            <label class="col-md-3 form-control-label m-2 p-0 mr-0 order-md-1 order-1"><strong>Inicio</strong></label>
                            <label class="col-md-3 form-control-label m-2 p-0 mr-0 order-md-2 order-3"><strong>Fin</strong></label>
                            <label class="col-md-4 form-control-label m-2 p-0 order-md-3 order-5"><strong>Color</strong></label>
                            <div class="col-12 col-md-3 mb-2 order-md-4 order-2">
                                <date-picker name="date" v-model="start" :config="options"></date-picker>
                            </div>
                            <div class="col-12 col-md-3 mb-2 order-md-5 order-4">
                                <date-picker name="date" v-model="end" :config="options"></date-picker>
                            </div>
                            <div class="col col-md-3 mb-2 order-md-6 order-1 order-6">
                                <select class="form-control" v-model="clase">
                                    <option value="red" style="background-color: rgba(217,83,79);color: #FFFFFF;">Rojo</option>
                                    <option value="blue" style="background-color: rgba(66,139,202);color: #FFFFFF;">Azul</option>
                                    <option value="green" style="background-color: rgba(17, 192, 32, 0.9);color: #FFFFFF;">Verde</option>
                                    <option value="orange" style="background-color: rgba(253, 165, 0, 0.945);color: #FFFFFF;">Naranja</option>
                                    <option value="purple" style="background-color: rgba(181, 32, 250, 0.9);color: #FFFFFF;">Morado</option>
                                    <option value="yellow" style="background-color: rgba(250, 234, 9, 0.986;">Amarillo</option>
                                </select>
                            </div>
                            <div class="col-2 col-md-1 mb-2 order-md-7 order-7">
                                <div v-if="clase=='red'" class="m-2 clrred"></div>
                                <div v-if="clase=='blue'" class="m-2 clrblue"></div>
                                <div v-if="clase=='green'" class="m-2 clrgreen"></div>
                                <div v-if="clase=='orange'" class="m-2 clrorange"></div>
                                <div v-if="clase=='purple'" class="m-2 clrpurple"></div>
                                <div v-if="clase=='yellow'" class="m-2 clryellow"></div>
                            </div>
                        </div>
                        <div class="form-group row m-0">
                            <label class="col-md-3 form-control-label m-2 p-0 mr-0" for="text-input"><strong>Título</strong></label>
                            <div class="col-md-9">
                                <input type="text" v-model="title" class="form-control" placeholder="Titulo de la actividad"/>
                            </div>
                        </div>
                        <div class="form-group row m-0">
                            <div class="col-10">
                                <label for=""><strong>Notas:</strong></label>
                                <textarea placeholder="Contenido" class="form-control rounded-0 noresize" rows="3" maxlength="256" v-model="content"></textarea>
                            </div>
                            <template v-if="isEdition">
                            <div class="col-md-1">
                                <label for=""><strong>Actividad Completada:</strong></label>&nbsp;
                                 <toggle-button @change="cambiarEstadoEvent(eventid)" v-model="btnComp" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            </template>
                        </div>
                        <!-- <div class="form-group row m-0">
                            <label class="col-md-3 form-control-label m-2 p-0 mr-0" for="text-area"><strong>Contendio</strong></label>
                            <div class="col-md-9">
                                <textarea placeholder="Contenido" class="form-control rounded-0 noresize" rows="3" maxlength="256" v-model="content"></textarea>
                            </div>&nbsp;
                        </div> -->
                        <div v-show="errorEvent" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjEvent" :key="error" v-text="error"></div>
                            </div>
                        </div>
                        <hr class="d-block d-sm-block d-md-none">
                        <div class="float-right d-block d-sm-block d-md-none">
                            <button type="button" v-if="isEdition==false" class="btn btn-primary" @click="registrarEvento()">Guardar</button>
                            <button type="button" v-if="isEdition" class="btn btn-primary" @click="actualizarEvento()">Actualizar</button>
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                    <button type="button" v-if="isEdition==false" class="btn btn-primary" @click="registrarEvento()">Guardar</button>
                    <button type="button" v-if="isEdition" class="btn btn-primary" @click="actualizarEvento()">Actualizar</button>
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar/actualizar actividad-->

    <!--Inicio del modal visualizar actividad-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content content-event2">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <!--  <div class="row">
                        <div class="col clrblue">1</div>
                        <div class="col">2</div>
                        <div class="col clrblue">3</div>
                        <div class="col">4</div>
                        <div class="col clrblue">5</div>
                        <div class="col">6</div>
                        <div class="col clrblue">7</div>
                        <div class="col">8</div>
                        <div class="col clrblue">9</div>
                        <div class="col">10</div>
                        <div class="col clrblue">11</div>
                        <div class="col">12</div>
                    </div> -->
                    <!-- INF CLIENTE -->
                    <div class="form-group row">
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <h1 for="" class="float-left"><strong v-text="cliente"></strong></h1>
                            </div>
                        </div>&nbsp;
                        <div class="col-md-2 text-center sinpadding" v-if="telefono">
                            <div class="form-group">
                                <label for=""><strong>Teléfono</strong></label>
                                <h6 for=""><strong v-text="telefono"></strong></h6>
                            </div>
                        </div>&nbsp;
                        <div class="col-md-2 text-center sinpadding" v-if="tipo">
                            <div class="form-group">
                                <label for=""><strong>Tipo de cliente</strong></label>
                                <h6 for=""><strong v-text="tipo"></strong></h6>
                            </div>
                        </div>&nbsp;
                        <div class="col-md-2 text-center sinpadding" v-if="rfc">
                            <div class="form-group">
                                <label for=""><strong>RFC</strong></label>
                                <h6 for=""><strong v-text="rfc"></strong></h6>
                            </div>
                        </div>&nbsp;
                        <div class="col-md-4 text-center sinpadding" v-if="company">
                            <div class="form-group">
                                <label for=""><strong>Contacto</strong></label>
                                <h6 for=""><strong> {{company}} | {{tel_company}}</strong></h6>
                            </div>
                        </div>&nbsp;

                        <!-- <div class="col-md-3 text-center sinpadding" v-if="observacion">
                            <div class="form-group">
                                <label for=""><strong>Observaciones</strong></label>
                                <h6 for=""><strong> {{observacion}}</strong></h6>
                            </div>
                        </div>&nbsp; -->
                    </div>
                    <hr>
                    <div :class="['col-md','caja2-' + clase,'m-0']">
                        <div class="row m-0 p-0">
                            <div class="col">
                                <template v-if="estado">
                                    <p class="text-success font-weight-bold float-right" style="font-size: 25px;">Completada <i class="fa fa-check-circle-o" aria-hidden="true"></i></p>
                                </template>
                                <template v-else>
                                    <p class="text-danger font-weight-bold float-right" style="font-size: 25px;">Incompleta <i class="fa fa-times-circle-o" aria-hidden="true"></i></p>
                                </template>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md col-10">
                                <h4 v-text="title"></h4>
                                <div class="form-inline">
                                    <div class="form-group mb-2">
                                        <div class="input-group">
                                            <p><small class="text-muted"><i class="fa fa-clock-o"></i>Inicio {{ start }}</small></p>&nbsp;
                                            <p><small class="text-muted"><i class="fa fa-clock-o"></i>Final {{ end }}</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md col-1">
                                <button type="button" class="btn btn-sm btntask float-right" @click="abriModalEditar(selectedEvent)">
                                    <i class="fa fa-pencil"></i>
                                </button>&nbsp;
                                <button type="button" class="btn btn-sm btntask float-right" @click="eliminarEvento(eventid)">
                                    <i class="fa fa-trash"></i>
                                </button>&nbsp;
                            </div>
                            <div class="col-md-12">
                                <p v-text="content"></p> <br>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" v-if="usrol == 1">
                        <div class="col-md-6 text-center mt-3">
                            <div class="form-group">
                                <h3 for="" class="float-left">Autor: <strong v-text="usuario"></strong></h3>
                            </div>
                        </div>&nbsp;
                    </div>
                    <hr class="d-block d-sm-block d-md-none">
                    <div class="float-right d-block d-sm-block d-md-none">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                    </div>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal visualizar actividad-->
  </main>
</template>

<script>
import Vue from 'vue';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import 'vue-cal/dist/i18n/es.js';
import moment from 'moment';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import vSelect from 'vue-select';
import ToggleButton from 'vue-js-toggle-button';
Vue.use(datePicker);
Vue.use(ToggleButton);
//Vue.component('date-picker', VueBootstrapDatetimePicker);
export default {
    components: { VueCal,vSelect },
    data() {
        return {
            events: [],
            modal: 0,
            modal2: 0,
            selectedEvent: {},
            tituloModal: "",
            eventid : "",
            idusuario : "",
            title : "",
            content : "",
            clase : "",
            start : "",
            end : "",
            estado : 0,
            options: {
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: false,
                showClear: true,
                showClose: true,
                daysOfWeekDisabled: [0],
                minDate: moment(),
                maxDate: moment().add(60, 'days'),
            },
            isEdition : false,
            isView : false,
            dateToday : "",
            errorEvent: 0,
            errorMostrarMsjEvent: [],
            idcliente : "",
            cliente : "",
            rfc : "",
            telefono : "",
            ciudad : "",
            domicilio : "",
            company : "",
            tel_company : "",
            email : "",
            tipo : "",
            observacion : "",
            arrayCliente : [],
            btnComp : false,
            usrol : 0,
            usuario : ""
        };
    },
    computed:{
        getDateToday : function(){
            let me = this;
            let date = "";
            moment.locale('es');
            date = moment().format('YYYY-MM-DD');
            me.dateToday = moment().format('YYYY-MM-DD');
            return date;
        }
    },
    methods: {
        listarEventos(){
            let me=this;
            axios.get('/event').then(function (response) {
                var respuesta= response.data;
                me.events = response.data.eventos;
                /* console.log("Rol: " + respuesta.userrol); */
                me.usrol = respuesta.userrol;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        abrirModal(){
            this.modal = 1;
            this.tituloModal = "Nueva Actividad";
            this.idusuario = "";
            this.title = "";
            this.content = "";
            this.clase = "green";
            this.start = "";
            this.end = "";
            this.isEdition = false;
            this.estado = 0;
            this.idcliente = "";
            this.cliente = "";
            this.rfc = "";
            this.telefono = "";
            this.ciudad = "";
            this.domicilio = "";
            this.company = "";
            this.tel_company = "";
            this.email = "";
            this.tipo = "";
            this.observacion = "";
            this.usuario = "";
            this.btnComp = false;
        },
        abriModalEditar(Event){
            this.modal = 1;
            this.modal2 = 0;
            this.tituloModal = "Editar " + Event.title;
            this.eventid = Event.id;
            this.idusuario = Event.userid;
            this.title = Event.title;
            this.content = Event.content;
            this.clase = Event.class;
            this.start = Event.start;
            this.end = Event.end;
            this.estado = Event.estado;

            this.idcliente = Event.idcliente;
            this.cliente = Event.cliente;
            this.rfc = Event.rfc;
            this.telefono = Event.telefono;
            this.ciudad = Event.ciudad;
            this.domicilio = Event.domicilio;
            this.company = Event.company;
            this.tel_company = Event.tel_company;
            this.email = Event.email;
            this.tipo = Event.tipo;
            this.observacion = Event.observacion;
            this.errorMostrarMsjEvent = [];

            if(this.estado){
                this.btnComp = true;
            }
        },
        onEventClick (event, e) {
            this.selectedEvent = event;
            this.modal2 = 1;
            this.tituloModal = this.selectedEvent.title;
            this.eventid = this.selectedEvent.id;
            this.idusuario = this.selectedEvent.userid;
            this.title = this.selectedEvent.title;
            this.content = this.selectedEvent.content;
            this.clase = this.selectedEvent.class;
            this.start = this.selectedEvent.start;
            this.end = this.selectedEvent.end;
            this.estado = this.selectedEvent.estado;

            this.idcliente = this.selectedEvent.idcliente;
            this.cliente = this.selectedEvent.cliente;
            this.rfc = this.selectedEvent.rfc;
            this.telefono = this.selectedEvent.telefono;
            this.ciudad = this.selectedEvent.ciudad;
            this.domicilio = this.selectedEvent.domicilio;
            this.company = this.selectedEvent.company;
            this.tel_company = this.selectedEvent.tel_company;
            this.email = this.selectedEvent.email;
            this.tipo = this.selectedEvent.tipo;
            this.observacion =  this.selectedEvent.observacion;
            this.usuario =  this.selectedEvent.user;
            this.isEdition = true;
            this.isView = true;
            e.stopPropagation()

            this.options.minDate = this.start;

            if(this.estado){
                this.btnComp = true;
            }

            /* console.log("MinDate EventClick " + this.options.minDate); */

           /*  var minDateCR = moment(this.options.minDate).format('YYYY-MM-DD HH:mm:ss');
            console.log("MinDate = "+ minDateCR);
            console.log("StarDate = "+ this.start); */

        },
        cerrarModal() {
            this.modal = 0;
            this.selectedEvent = {};
            this.tituloModal = "";
            this.eventid = "";
            this.idusuario = "";
            this.title = "";
            this.content = "";
            this.clase = "";
            this.start = "";
            this.end = "";
            this.estado = 0;
            this.idcliente = "";
            this.cliente = "";
            this.rfc = "";
            this.telefono = "";
            this.ciudad = "";
            this.domicilio = "";
            this.company = "";
            this.tel_company = "";
            this.email = "";
            this.tipo = "";
            this.observacion = "";
            this.isEdition = false;
            this.btnComp = false;
            this.usuario = "";
            this.options.minDate = moment().format('YYYY-MM-DD HH:mm:ss');
            this.listarEventos();

            /* console.log("MinDate at Close " + this.options.minDate); */
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.selectedEvent = {};
            this.tituloModal = "";
            this.eventid = "";
            this.idusuario = "";
            this.title = "";
            this.content = "";
            this.clase = "";
            this.start = "";
            this.end = "";
            this.estado = 0;
            this.idcliente = "";
            this.cliente = "";
            this.rfc = "";
            this.telefono = "";
            this.ciudad = "";
            this.domicilio = "";
            this.company = "";
            this.tel_company = "";
            this.email = "";
            this.tipo = "";
            this.observacion = "";
            this.usuario = "";
            this.isEdition = false;
            this.btnComp = false;
            this.options.minDate = moment().format('YYYY-MM-DD HH:mm:ss');
            this.listarEventos();

            /* console.log("MinDate at Close " + this.options.minDate); */
        },
        selectCliente(search,loading){
            let me=this;
            loading(true)
            var url= '/cliente/selectCliente?filtro='+search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                q: search
                me.arrayCliente=respuesta.clientes;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosCliente(val1){
            let me = this;
            me.loading = true;
            me.idcliente = val1.id;
            me.cliente = val1.nombre;
            me.rfc =  val1.rfc;
            me.tipo = val1.tipo;
            me.telefono = val1.telefono;
            me.company = val1.company;
            me.tel_company = val1.tel_company;
            me.observacion = val1.observacion;
        },
        validarEvent() {
            this.errorEvent = 0;
            this.errorMostrarMsjEvent = [];

            if (!this.title) this.errorMostrarMsjEvent.push("El titulo no puede estar vacio...");
            if (!this.content) this.errorMostrarMsjEvent.push("El contenido no puede estar vacio...");
            if (!this.start) this.errorMostrarMsjEvent.push("Selecciona la fecha de inicio...");
            if (!this.end) this.errorMostrarMsjEvent.push("Selecciona la fecha de final...");
            if (this.idcliente==0) this.errorMostrarMsjEvent.push("Seleccione un cliente");
            if (this.errorMostrarMsjEvent.length) this.errorEvent = 1;

            return this.errorEvent;
        },
        registrarEvento(){
            if (this.validarEvent()) {
                return;
            }

            let me = this;

            axios.post("/event/registrar", {
                'start': this.start,
                'end': this.end,
                'title': this.title,
                'content': this.content,
                'clase': this.clase,
                'idcliente' : this.idcliente
            })
            .then(function(response) {
                me.cerrarModal();
                me.listarEventos();
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        actualizarEvento(){
            if (this.validarEvent()) {
                return;
            }
            let me = this;
            axios.put("/event/actualizar", {
                'id': this.eventid,
                'start': this.start,
                'end': this.end,
                'title': this.title,
                'content': this.content,
                'clase': this.clase,
                'idcliente' : this.idcliente,
                'idusuario' : this.idusuario,
                'estado' : this.estado
            })
            .then(function(response) {
                me.cerrarModal();
                me.listarEventos();
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        eliminarEvento(id){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de eliminar esta actividad?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.delete('/event/'+ id).then(function(response) {
                        me.cerrarModal2();
                        me.listarEventos();
                        swalWithBootstrapButtons.fire(
                            "Eliminado!",
                            "El evento ha sido eliminado con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        cambiarEstadoEvent(id){
            let me = this;
            if(me.btnComp == true){
                me.estado = 1;
            }else{
                me.estado = 0;
            }
            var antestado = me.estado;
            axios.put('/event/completar',{
                'id': id,
                'estado' : this.estado
            }).then(function (response) {

                if(antestado == 1){
                  Swal.fire(
                    "Completado!",
                    "La actividad ha sido marcada como completada con éxito.",
                    "success"
                    )
                }else{
                    Swal.fire(
                        "Cambiado!",
                        "La actividad ha sido marcada como incompleta.",
                        "warning"
                    )
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.listarEventos();
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
    .content-event{
        height: 660px !important;
    }
    .content-event2{
        height: 650px !important;
    }
    /* Green-theme. */
    .vuecal__menu, .vuecal__cell-events-count {background-color: #42b983;}
    .vuecal__menu button {border-bottom-color: #fff;color: #fff;}
    .vuecal__menu button.active {background-color: rgba(255, 255, 255, 0.15);}
    .vuecal__title-bar {background-color: #e4f5ef;}
    .vuecal__cell.today, .vuecal__cell.current {background-color: rgba(240, 240, 255, 0.4);}
    .vuecal:not(.vuecal--day-view) .vuecal__cell.selected {background-color: rgba(235, 255, 245, 0.4);}
    .vuecal__cell.selected:before {border-color: rgba(66, 185, 131, 0.5);}
    /* Different color for different event types. */
    .vuecal__event.red {background-color: rgba(217,83,79);border: 1px solid rgb(217,83,79);color: #fff;}
    .vuecal__event.blue {background-color: rgba(66,139,202);border: 1px solid rgb(66,139,202);color: #fff;}
    .vuecal__event.green {background-color: rgba(17, 192, 32, 0.9);border: 1px solid rgb(17, 192, 32, 0.9);color: #fff;}
    .vuecal__event.orange {background-color: rgba(253, 165, 0, 0.945);border: 1px solid rgb(253, 165, 0, 0.945);color: #fff;}
    .vuecal__event.purple {background-color: rgba(181, 32, 250, 0.9);border: 1px solid rgb(181, 32, 250, 0.9);color: #fff;}
    .vuecal__event.yellow {background-color: rgba(250, 234, 9, 0.986);border: 1px solid rgb(250, 234, 9, 0.986);color: #000;}


    .clrred{
        background-color: rgba(217,83,79) !important;
        width: 20px;
        height: 20px;
    }
    .clrblue{
        background-color:rgba(66,139,202) !important;
        width: 20px;
        height: 20px;
    }
    .clrgreen{
        background-color:rgba(17, 192, 32, 0.9) !important;
        width: 20px;
        height: 20px;
    }
    .clrorange{
        background-color: rgba(253, 165, 0, 0.945) !important;
        width: 20px;
        height: 20px;
    }
    .clrpurple{
        background-color:rgba(181, 32, 250, 0.9) !important;
        width: 20px;
        height: 20px;
    }
    .clryellow{
        background-color: rgba(250, 234, 9, 0.986) !important;
        width: 20px;
        height: 20px;
    }
    textarea.noresize{
        height: 100px;
        resize: none;
        /*  min-height: 60px;
        max-height: 300px; */
    }
    div.caja2-red{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(217,83,79);
        box-shadow: 0 1px 6px rgba(217,83,79);
        height: 250px;
    }
    div.caja2-blue{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(66,139,202);
        box-shadow: 0 1px 6px rgba(66,139,202);
        height: 250px;
    }
    div.caja2-green{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(17, 192, 32, 0.9);
        box-shadow: 0 1px 6px rgba(17, 192, 32, 0.9);
        height: 250px;
    }
    div.caja2-orange{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(253, 165, 0, 0.945);
        box-shadow: 0 1px 6px rgba(253, 165, 0, 0.945);
        height: 250px;
    }
    div.caja2-purple{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(181, 32, 250, 0.9);
        box-shadow: 0 1px 6px rgba(181, 32, 250, 0.9);
        height: 250px;
    }
    div.caja2-yellow{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(250, 234, 9, 0.986);
        box-shadow: 0 1px 6px rgba(250, 234, 9, 0.986);
        height: 250px;
    }

</style>

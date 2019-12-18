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
          <i class="fa fa-align-justify"></i> Clientes
          <button type="button" @click="abrirModal('persona','registrar')" class="btn btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
            <div class="form-inline">
                <div class="form-group mb-2 col-12">
                    <div class="input-group">
                        <select class="form-control mb-1" v-model="criterio">
                            <option value="nombre">Nombre</option>
                            <option value="rfc">RFC</option>
                            <option value="email">Correo electrónico</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control mb-1" placeholder="Texto a buscar">
                        <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary mb-1"><i class="fa fa-search mb-1"></i></button>
                    </div>
                </div>
            </div>
            <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Domicilio</th>
                            <th>Teléfono</th>
                            <th>Contacto</th>
                            <th>Correo electrónico</th>
                            <th>RFC</th>
                            <th>Tipo</th>
                            <th>Observaciones</th>
                            <th>Vendedor</th>
                        </tr>
                    </thead>
                    <tbody v-if="arrayPersona.length">
                        <tr v-for="persona in arrayPersona" :key="persona.id">
                            <td>
                            <button type="button" @click="abrirModal('persona','actualizar',persona)" class="btn btn-warning btn-sm">
                                <i class="icon-pencil"></i>
                            </button>
                            </td>
                            <td v-text="persona.nombre"></td>
                            <td> {{ persona.ciudad}} {{persona.domicilio}}  </td>
                            <td v-text="persona.telefono"></td>
                            <td v-if="persona.company">{{persona.company}} - {{persona.tel_company}}</td>
                            <td v-else></td>
                            <td v-text="persona.email"></td>
                            <td v-text="persona.rfc"></td>
                            <td v-text="persona.tipo"></td>
                            <td v-text="persona.observacion"></td>
                            <td v-text="persona.vendedor"></td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="10" class="text-center">
                                <strong>NO hay clientes agregados o con ese criterio...</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, criterio)">Sig</a>
                    </li>
                </ul>
            </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content content-task">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5>Seleccione el tipo de cliente</h5>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#tabCliente" class="nav-link active" data-toggle="tab">Persona Física</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabEmpresa" class="nav-link" data-toggle="tab">Persona Moral</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- TAB CLIENTE -->
                                <div class="tab-pane active" id="tabCliente" role="tab panel">
                                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3" v-if="userrol==1">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Vendedor</span>
                                                </div>
                                                <select class="form-control" v-model="userid">
                                                    <option value="0" disabled>Seleccione un vendedor</option>
                                                    <option v-for="vendedor in arrayVendedores" :key="vendedor.id" :value="vendedor.id" v-text="vendedor.nombre"></option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Tipo</span>
                                                </div>
                                                <select class="form-control" v-model="tipo">
                                                    <option value="" disabled>Seleccione el tipo de cliente</option>
                                                    <option value="PROSPECTO">PROSPECTO</option>
                                                    <option value="PRIMER CONTACTO">PRIMER CONTACTO</option>
                                                    <option value="CLIENTE FINAL">CLIENTE FINAL</option>
                                                    <option value="CLIENTE MARMOLERO">CLIENTE MARMOLERO</option>
                                                    <option value="CLIENTE COCINERO">CLIENTE COCINERO</option>
                                                    <option value="CLIENTE ARQ">CLIENTE ARQ</option>
                                                    <option value="GENERAL Y PAGOS">GENERAL Y PAGOS</option>
                                                    <option value="NO PROMOVER">NO PROMOVER</option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Nombre</span>
                                                </div>
                                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona física"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Teléfono</span>
                                                </div>
                                                <input type="text" v-model="telefono" maxlength="13" class="form-control" placeholder="Teléfono de la persona física"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Correo electrónico</span>
                                                </div>
                                                <input type="text" v-model="email" class="form-control" placeholder="Correo electrónico"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12  col-xl-8  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Direccion</span>
                                                </div>
                                                <input type="text" v-model="domicilio" class="form-control" placeholder="Domicilio"/>
                                                <input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad y estado"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-xl-8  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Contacto</span>
                                                </div>
                                                <input type="text" v-model="company" class="form-control" placeholder="Nombre de contacto"/>
                                                <input type="text" maxlength="13" v-model="tel_company" class="form-control" placeholder="Teléfono de contacto"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">RFC</span>
                                                </div>
                                                <input type="text" v-model="rfc" maxlength="13" class="form-control" placeholder="RFC persona física"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Uso del CFDI</span>
                                                </div>
                                                <select class="form-control" v-model="cfdi">
                                                    <option value="" disabled>Seleccione</option>
                                                    <option value="G01-Adquisicion de mercacias">G01-Adquisicion de mercacias</option>
                                                    <option value="G02-Devoluciones,descuentos o bonificaciones">G02-Devoluciones,descuentos o bonificaciones</option>
                                                    <option value="G03-Gastos en general">G03-Gastos en general</option>
                                                    <option value="G04-Pago con tarjeta de credito">G04-Pago con tarjeta de credito</option>
                                                    <option value="G28-Pago con tarjeta de debito">G28-Pago con tarjeta de debito</option>
                                                    <option value="P01-Por definir">P01-Por definir</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-8 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Observaciones</span>
                                                </div>
                                                <textarea class="form-control rounded-0" style="resize: none;" rows="3" maxlength="256" v-model="observacion"></textarea>
                                            </div>
                                        </div>
                                        <div v-show="errorPersona" class="form-group row div-error">
                                            <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- TAB EMPRESA -->
                                <div class="tab-pane" id="tabEmpresa" role="tabpanel">
                                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3" v-if="userrol==1">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Vendedor</span>
                                                </div>
                                                <select class="form-control" v-model="userid">
                                                    <option value="0" disabled>Seleccione un vendedor</option>
                                                    <option v-for="vendedor in arrayVendedores" :key="vendedor.id" :value="vendedor.id" v-text="vendedor.nombre"></option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Tipo</span>
                                                </div>
                                                <select class="form-control" v-model="tipo">
                                                    <option value="" disabled>Seleccione el tipo de cliente</option>
                                                    <option value="PROSPECTO">PROSPECTO</option>
                                                    <option value="PRIMER CONTACTO">PRIMER CONTACTO</option>
                                                    <option value="CLIENTE FINAL">CLIENTE FINAL</option>
                                                    <option value="CLIENTE MARMOLERO">CLIENTE MARMOLERO</option>
                                                    <option value="CLIENTE COCINERO">CLIENTE COCINERO</option>
                                                    <option value="CLIENTE ARQ">CLIENTE ARQ</option>
                                                    <option value="GENERAL Y PAGOS">GENERAL Y PAGOS</option>
                                                    <option value="NO PROMOVER">NO PROMOVER</option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Nombre</span>
                                                </div>
                                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona moral"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Teléfono</span>
                                                </div>
                                                <input type="text" v-model="telefono" maxlength="13" class="form-control" placeholder="Teléfono de la persona moral"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Correo electrónico</span>
                                                </div>
                                                <input type="text" v-model="email" class="form-control" placeholder="Correo electrónico"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12  col-xl-8  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Direccion</span>
                                                </div>
                                                <input type="text" v-model="domicilio" class="form-control" placeholder="Domicilio"/>
                                                <input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad y estado"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-xl-8  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Contacto</span>
                                                </div>
                                                <input type="text" v-model="company" class="form-control" placeholder="Nombre de contacto"/>
                                                <input type="text" maxlength="13" v-model="tel_company" class="form-control" placeholder="Teléfono de contacto"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-6 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">RFC</span>
                                                </div>
                                                <input type="text" v-model="rfc" maxlength="13" class="form-control" placeholder="RFC persona moral"/>
                                            </div>
                                            <div class="input-group input-group-sm col-12 col-lg-6  mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Uso del CFDI</span>
                                                </div>
                                                <select class="form-control" v-model="cfdi">
                                                    <option value="" disabled>Seleccione</option>
                                                    <option value="G01-Adquisicion de mercacias">G01-Adquisicion de mercacias</option>
                                                    <option value="G02-Devoluciones,descuentos o bonificaciones">G02-Devoluciones,descuentos o bonificaciones</option>
                                                    <option value="G03-Gastos en general">G03-Gastos en general</option>
                                                    <option value="G04-Pago con tarjeta de credito">G04-Pago con tarjeta de credito</option>
                                                    <option value="G28-Pago con tarjeta de debito">G28-Pago con tarjeta de debito</option>
                                                    <option value="P01-Por definir">P01-Por definir</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm col-12 col-lg-8 mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Observaciones</span>
                                                </div>
                                                <textarea class="form-control rounded-0" style="resize: none;" rows="3" maxlength="256" v-model="observacion"></textarea>
                                            </div>
                                        </div>
                                        <div v-show="errorPersona" class="form-group row div-error">
                                            <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="d-block d-sm-block d-md-none">
                    <div class="float-right d-block d-sm-block d-md-none">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
export default {
    data() {
        return {
            persona_id: 0,
            nombre: "",
            tipo_documento: "",
            num_documento: "",
            ciudad: "",
            domicilio: "",
            telefono: "",
            email: "",
            rfc: "",
            tipo: "",
            observacion: "",
            cfdi : "",
            userid : "",
            userrol: "",
            company : "",
            tel_company : "",
            arrayPersona: [],
            modal: 0,
            tituloModal: "",
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
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
            arrayVendedores : []
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
        listarPersona (page,buscar,criterio){
            let me=this;
            var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayPersona = respuesta.personas.data;
                me.pagination= respuesta.pagination;
                me.userid = respuesta.userid;
                me.userrol = respuesta.userrol;
                /* console.log("Rol: " + respuesta.userrol);
                console.log("ID: " + respuesta.userid); */
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cambiarPagina(page,buscar,criterio){
            let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersona(page,buscar,criterio);
        },
        registrarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;

            axios.post("/cliente/registrar", {
                'nombre': this.nombre,
                'tipo_documento': this.tipo_documento,
                'num_documento': this.num_documento,
                'ciudad': this.ciudad,
                'domicilio': this.domicilio,
                'telefono': this.telefono,
                'company' : this.company,
                'tel_company' : this.tel_company,
                'email': this.email,
                'rfc': this.rfc,
                'tipo': this.tipo,
                'idusuario' : this.userid,
                'observacion':this.observacion,
                'cfdi' : this.cfdi
            })
            .then(function(response) {
                me.cerrarModal();
                me.listarPersona(1,'','nombre');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        actualizarPersona() {
            if (this.validarPersona()) {
                return;
            }
            let me = this;
            axios.put("/cliente/actualizar", {
                'nombre': this.nombre,
                'tipo_documento': this.tipo_documento,
                'num_documento': this.num_documento,
                'ciudad': this.ciudad,
                'domicilio': this.domicilio,
                'telefono': this.telefono,
                'company' : this.company,
                'tel_company' : this.tel_company,
                'email': this.email,
                'rfc': this.rfc,
                'id': this.persona_id,
                'tipo': this.tipo,
                'cfdi' : this.cfdi,
                'idusuario' : this.userid,
                'observacion':this.observacion
            })
            .then(function(response) {
                me.cerrarModal();
                me.listarPersona(1,'','nombre');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];

            if (!this.nombre)
                this.errorMostrarMsjPersona.push("El nombre del cliente no puede estar vacío.");

            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.nombre = "";
            this.tipo_documento = "";
            this.num_documento = "";
            this.ciudad = "";
            this.domicilio = "";
            this.telefono = "";
            this.company = "";
            this.tel_company = "";
            this.email = "";
            this.rfc = "";
            this.errorPersona = 0;
            this.tipo = "";
            this.observacion = "";
            this.listarPersona(1,'','nombre');
        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "persona": {
                    switch (accion) {
                        case "registrar": {
                            this.modal = 1;
                            this.tituloModal = "Registrar Cliente";
                            this.nombre = "";
                            this.tipo_documento = "";
                            this.num_documento = "";
                            this.ciudad = "";
                            this.domicilio = "";
                            this.telefono = "";
                            this.company = "";
                            this.tel_company = "";
                            this.email = "";
                            this.rfc = "";
                            this.tipo = "";
                            this.observacion = "";
                            this.tipoAccion = 1;
                            break;
                        }
                        case "actualizar": {
                            this.modal = 1;
                            this.tituloModal = "Actualizar Cliente";
                            this.tipoAccion = 2;
                            this.persona_id = data["id"];
                            this.nombre = data["nombre"];
                            this.tipo_documento = data["tipo_documento"];
                            this.num_documento = data["num_documento"];
                            this.ciudad = data["ciudad"];
                            this.domicilio = data["domicilio"];
                            this.telefono = data["telefono"];
                            this.company = data["company"];
                            this.tel_company = data["tel_company"];
                            this.email = data["email"];
                            this.rfc = data["rfc"];
                            this.tipo = data["tipo"];
                            this.observacion = data["observacion"];
                            this.userid = data["idvendedor"];
                            break;
                        }
                    }
                }
            }
            this.selectVendedor();
        },
        selectVendedor(){
            let me=this;
            var url= '/user/selectUsuario';

            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayVendedores = respuesta.usuarios;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    mounted() {
        this.listarPersona(1,this.buscar, this.criterio);
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
    .content-task{
        /* height: 700px !important; */
        /* height : auto  !important; */
        min-height: 652px !important;
    }
    .div-error {
      display: flex;
      justify-content: center;
    }
    .text-error {
      color: red !important;
      font-weight: bold;
    }
</style>

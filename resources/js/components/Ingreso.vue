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
          <i class="fa fa-align-justify"></i> Ingresos
          <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!-- Listado -->
        <template v-if="listado">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                        <option value="num_comprobante">No° Comprobante</option>
                        <option value="fecha_hora">Fecha</option>
                        <option value="estado">Estado</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Proveedor</th>
                                <th>Tipo Comprobante</th>
                                <th>No° Comprobante</th>
                                <th>Fecha Hora</th>
                                <th>Total</th>
                                <th>Impuesto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                <td>
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button>&nbsp;
                                        <template v-if="ingreso.estado=='Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                </td>
                                <td v-text="ingreso.usuario"></td>
                                <td v-text="ingreso.nombre"></td>
                                <td v-text="ingreso.tipo_comprobante"></td>
                                <td v-text="ingreso.num_comprobante"></td>
                                <td v-text="ingreso.fecha_hora"></td>
                                <td v-text="ingreso.total"></td>
                                <td v-text="ingreso.impuesto"></td>
                                <td v-text="ingreso.estado "></td>
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
        </template>
        <!-- Fin Listado -->

        <!-- Detalle -->
        <template v-else>
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="">Proveedor (*)</label>
                                <v-select
                                    :on-search="selectProveedor"
                                    label="nombre"
                                    :options="arrayProveedor"
                                    placeholder="Buscar Proveedores..."
                                    :onChange="getDatosProveedor"
                                >

                                </v-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="">Impuesto (*)</label>
                        <input type="text" class="form-control" v-model="impuesto">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Tipo Comprobante (*) </label>
                            <select v-model="tipo_comprobante" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="NOTA">Nota</option>
                                <option value="FACTURA">Factura</option>
                                <option value="TICKET">Ticket</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Número de Comprobante (*)</label>
                            <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                        </div>
                    </div>
                </div>
                <div class="form-group row border">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Artículo (*) <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span> </label>
                            <div class="form-inline">
                                <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()"  placeholder="Ingrese el artículo" >
                                <button @click="abrirModal()" class="btn btn-primary">...</button>
                                <input type="text" readonly class="form-control" v-model="articulo">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese la cantidad)</span></label>
                            <input type="number" min="0" value="0" step="any" class="form-control" v-model="cantidad">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Precio <span style="color:red;" v-show="precio_compra==0">(*Ingrese el precio)</span></label>
                            <input type="number" min="0" value="0" class="form-control" v-model="precio_compra">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-grousp">
                            <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>SubTotal</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayDetalle.length">
                                <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                    <td>
                                        <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-close"></i>
                                        </button>
                                    </td>
                                    <td v-text="detalle.articulo"></td>
                                    <td>
                                        <input v-model="detalle.cantidad" min="0" type="number" value="2" class="form-control">
                                    </td>
                                    <td>
                                        <input v-model="detalle.precio_compra" min="0" type="number" value="3" class="form-control">
                                    </td>
                                    <td>
                                       {{ detalle.precio_compra * detalle.cantidad }}
                                    </td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                    <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto=((total * impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                    <td>$ {{total=calcularTotal}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <strong>NO hay artículos agregados...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Fin detalle -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal listar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterioA">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                    <option value="codigo">Código</option>
                                </select>
                                <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>&nbsp;
                                <button type="button" @click="abrirModal2('articulo','registrar')" class="btn btn-secondary">
                                    <i class="icon-plus"></i>&nbsp;Nuevo
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead>
                            <tr class="text-center">
                                <th>Opciones</th>
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Material</th>
                                <th>Largo</th>
                                <th>Alto</th>
                                <th>Metros<sup>2</sup></th>
                                <th>Stock</th>
                                <th>Ubicacion</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                <td>
                                    <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                        <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td v-text="articulo.sku"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nombre_categoria"></td>
                                <td v-text="articulo.largo"></td>
                                <td v-text="articulo.alto"></td>
                                <td v-text="articulo.metros_cuadrados"></td>
                                <td v-text="articulo.stock"></td>
                                <td v-text="articulo.ubicacion"></td>
                                <td>
                                <div v-if="articulo.condicion">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
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

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Material</label>
                            <div class="col-md-9">
                            <select class="form-control" v-model="idcategoria_r">
                                <option value="0" disabled>Seleccione un material</option>
                                <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Código</label>
                            <div class="col-md-9">
                                <input type="text" v-model="codigo_r" class="form-control" placeholder="Código de barras"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">SKU</label>
                            <div class="col-md-9">
                                <input type="text" v-model="sku_r" class="form-control" placeholder="SKU"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_art" class="form-control" placeholder="Nombre del artículo"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Terminado</label>
                            <div class="col-md-9">
                                <input type="text" v-model="terminado" class="form-control" placeholder="Terminado"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Largo</label>
                            <div class="col-md-9">
                                <input type="number" v-model="largo" min="1" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Alto</label>
                            <div class="col-md-9">
                                <input type="number" min="1" v-model="alto" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Metros<sup>2</sup></label>
                            <div class="col-md-9">
                            <input type="number" min="1" v-model="metros_cuadrados" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Espesor</label>
                            <div class="col-md-9">
                                <input type="number" min="1" v-model="espesor" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Ubicación</label>
                            <div class="col-md-9">
                                <input type="text" v-model="ubicacion" class="form-control" placeholder="Ubicación"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                            <div class="col-md-9">
                                <input type="number" min="1" v-model="stock" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                            <div class="col-md-9">
                                <input type="email" v-model="descripcion_r" class="form-control" placeholder="Ingrese descripción"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">observacion</label>
                            <div class="col-md-9">
                                <input type="email" v-model="observacion_r" class="form-control" placeholder="Ingrese las observaciones"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Origen</label>
                            <div class="col-md-9">
                                <input type="text" v-model="origen" class="form-control" placeholder="Origen"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Fecha de llegada</label>
                            <div class="col-md-9">
                                <input type="date" v-model="fecha_llegada" class="form-control" placeholder="Fecha de llegada"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Imagen</label>
                            <div class="col-md-9">
                                <input type="file" :src="imagen" @change="obtenerImagen" class="form-control-file">

                            </div>
                        </div>
                        <figure>
                            <img width="300" height="200" class="img-responsive imgcenter" :src="imagen" alt="Foto del artículo">
                        </figure>
                        <div v-show="errorArticulo" class="form-group row div-error">
                            <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="(cerrarModal2(),listarArticulo(buscarA,criterioA))">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
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
import vSelect from 'vue-select';
export default {
    data() {
        return {
            ingreso_id: 0,
            idproveedor: 0,
            nombre: "",
            tipo_comprobante: "FACTURA",
            num_comprobante: "",
            impuesto: 0.16,
            idarticulo : 0,
            articulo : "",
            codigo: "",
            precio_compra : 0,
            cantidad : 0,
            total_impuesto : 0.0,
            total_parcial : 0.0,
            total: 0.0,

            arrayArticulo : [],
            arrayIngreso : [],
            arrayDetalle : [],
            arrayProveedor : [],

            listado : 1,
            modal: 0,
            modal2: 0,
            tituloModal: "",
            tipoAccion: 0,
            errorIngreso: 0,
            errorMostrarMsjIngreso: [],
            pagination : {
                'total'        : 0,
                'current_page' : 0,
                'per_page'     : 0,
                'last_page'    : 0,
                'from'         : 0,
                'to'           : 0,
            },
            offset : 3,
            criterio : 'num_comprobante',
            buscar : '',
            buscarA : '',
            criterioA : 'nombre',

            //Registrar artículo
            articulo_idr: 0,
            idcategoria_r :0,
            nombre_categoria_r : '',
            codigo_r : '',
            sku_r : '',
            nombre_art: '',
            terminado : '',
            largo : 0,
            alto : 0,
            metros_cuadrados : 0,
            espesor : 0,
            ubicacion : '',
            stock : 0,
            descripcion_r: '',
            observacion_r : '',
            origen : '',
            fecha_llegada : '',
            file : '',
            imagenMinatura : '',
            arrayArticulo_r: [],
            errorArticulo: 0,
            errorMostrarMsjArticulo: [],
            arrayCategoria : []

        };
    },
    components: {
        vSelect
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
            },

            calcularTotal : function(){
                let me=this;
                let resultado = 0;
                for(var i=0;i<me.arrayDetalle.length;i++){
                    resultado = resultado + (me.arrayDetalle[i].precio_compra * me.arrayDetalle[i].cantidad)
                }
                return resultado;
            },
            imagen(){
                return this.imagenMinatura;
            }
        },
    methods: {

        listarIngreso (page,buscar,criterio){
            let me=this;
            var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayIngreso = respuesta.ingresos.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectProveedor(search,loading){
            let me=this;
            loading(true)
            var url= '/proveedor/selectProveedor?filtro='+search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                q: search
                me.arrayProveedor=respuesta.proveedores;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
        },
        buscarArticulo(){

            let me = this;
            var url= '/articulo/buscarArticulo?filtro='+ me.codigo;

            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arrayArticulo=respuesta.articulos;

                if(me.arrayArticulo.length > 0){
                    me.articulo = me.arrayArticulo[0]['nombre'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
                }else{
                    me.articulo = 'No existe este artículo';
                    me.idarticulo = 0;
                }
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
                me.listarIngreso(page,buscar,criterio);
        },
        encuentra(id){
            let sw = 0;

            for(var i=0; i<this.arrayDetalle.length;i++){
                if(this.arrayDetalle[i].idarticulo==id){
                    sw = true;
                }
            }
            return sw;

        },
        eliminarDetalle(index){
            let me = this;
            me.arrayDetalle.splice(index,1);
        },
        agregarDetalle(){
            let me = this;

            if(me.idarticulo== 0 || me.precio_compra == 0 || me.cantidad == 0){
            }else{
                if(me.encuentra(me.idarticulo)){
                    Swal.fire({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este artículo ya se encuentra agregado!',
                    });
                    me.codigo = "";
                    me.idarticulo = 0;
                    me.articulo = "";
                    me.precio_compra = 0;
                    me.cantidad = 0;
                }else{
                    me.arrayDetalle.push({
                        idarticulo : me.idarticulo,
                        articulo : me.articulo,
                        cantidad : me.cantidad,
                        precio_compra : me.precio_compra
                    });
                    me.codigo = "";
                    me.idarticulo = 0;
                    me.articulo = "";
                    me.precio_compra = 0;
                    me.cantidad = 0;
                }
            }
        },
        registrarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;

            axios.post("/user/registrar", {
                'nombre': this.nombre,
                'tipo_documento': this.tipo_documento,
                'num_documento': this.num_documento,
                'ciudad': this.ciudad,
                'domicilio': this.domicilio,
                'telefono': this.telefono,
                'email': this.email,
                'rfc': this.rfc,
                'usuario' : this.usuario,
                'password' : this.password,
                'idrol' : this.idrol
            })
            .then(function(response) {
                me.cerrarModal();
                me.listarPersona(1,'','nombre');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        desactivarUsuario(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de desactivar este usuarío?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/user/desactivar', {
                        'id' : id
                    }).then(function(response) {
                        me.listarPersona(1,'','nombre');
                        swalWithBootstrapButtons.fire(
                            "Desactivado!",
                            "El usuarío ha sido desactivado con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        activarUsuario(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de activar este usuarío?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/user/activar', {
                        'id' : id
                    }).then(function(response) {
                        me.listarPersona(1,'','nombre');
                        swalWithBootstrapButtons.fire(
                            "Activado!",
                            "El usuarío ha sido activado con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];

            if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");
            if (!this.usuario) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
            if (!this.password) this.errorMostrarMsjPersona.push("La contraseña del usuario no puede estar vacía.");
            if (this.idrol == 0 ) this.errorMostrarMsjPersona.push("Debe seleccionar un rol de usuario");

            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
        mostrarDetalle(){
            this.listado = 0;
        },
        ocultarDetalle(){
            this.listado = 1;
        },
        cerrarModal() {
            this.modal = 0;
        },
        abrirModal() {
            this.arrayArticulo=[];
            this.modal = 1;
            this.tituloModal = "Seleccionar Artículos";
        },
        listarArticulo (buscar,criterio){
            let me=this;
            var url= '/articulo/listarArticulo?buscar=' + buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayArticulo = respuesta.articulos.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        agregarDetalleModal(data = []){

            let me = this;

            if(me.encuentra(data['id'])){
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este artículo ya se encuentra agregado!',
                });
                me.codigo = "";
                me.idarticulo = 0;
                me.articulo = "";
                me.precio_compra = 0;
                me.cantidad = 0;
            }else{
                me.arrayDetalle.push({
                    idarticulo : data['id'],
                    articulo : data['nombre'],
                    cantidad : 1,
                    precio_compra : 1
                });
            }
        },
        abrirModal2(modelo,accion){
            switch (modelo) {
                case "articulo": {
                    switch (accion) {
                        case "registrar": {
                            this.modal = 0;
                            this.modal2 = 1;
                            this.tituloModal = "Registrar Artículo";
                            this.idcategoria_r = 0;
                            this.codigo_r = '';
                            this.sku_r = '';
                            this.nombre_art = '';
                            this.terminado = '';
                            this.largo = 0;
                            this.alto= 0;
                            this.metros_cuadrados = 0;
                            this.espesor = 0;
                            this.ubicacion = '';
                            this.stock = 0;
                            this.descripcion= '';
                            this.observacion = '';
                            this.origen = '';
                            this.fecha_llegada = '';
                            this.file = '';
                            this.imagenMinatura = '';
                            break;
                        }
                    }
                }
            }
            this.selectCategoria();
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.modal = 1;
            this.tituloModal = "Seleccionar Artículos";
            this.arrayArticulo_r=[];
            this.idcategoria_r = 0;
            this.codigo_r = '';
            this.sku_r = '';
            this.nombre_art = '';
            this.terminado = '';
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.espesor = 0;
            this.ubicacion = '';
            this.stock = 0;
            this.descripcion= '';
            this.observacion = '';
            this.origen = '';
            this.fecha_llegada = '';
            this.file = '';
            this.errorArticulo = 0;
            this.imagenMinatura = '';
        },
        selectCategoria(){
            let me=this;
            var url= '/categoria/selectCategoria';
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayCategoria = respuesta.categorias;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        obtenerImagen(e){
            let img = e.target.files[0];
            this.file = img;
            this.cargarImagen(img);
        },
        cargarImagen(img){
            let reader = new FileReader();
            reader.onload = (e) => {
                this.imagenMinatura = e.target.result;
                this.file =  e.target.result;
            }
            reader.readAsDataURL(img);
        },
        validarArticulo() {
            this.errorArticulo = 0;
            this.errorMostrarMsjArticulo = [];
            if (this.idcategoria_r==0) this.errorMostrarMsjArticulo.push("Selecciona una categoría.");
            if (!this.nombre_art) this.errorMostrarMsjArticulo.push("El nombre del artículo no puede estar vacío.");
            if (!this.sku_r) this.errorMostrarMsjArticulo.push("El sku del artículo no puede estar vacío.");
            if (!this.terminado) this.errorMostrarMsjArticulo.push("El terminado del artículo no puede estar vacío.");
            if (!this.largo) this.errorMostrarMsjArticulo.push("El largo del artículo no puede estar vacío.");
            if (!this.alto) this.errorMostrarMsjArticulo.push("El alto del artículo no puede estar vacío.");
            if (!this.metros_cuadrados) this.errorMostrarMsjArticulo.push("Los metros cuadrados del artículo no pueden estar vacíos.");
            if (!this.espesor) this.errorMostrarMsjArticulo.push("El espesor del artículo no puede estar vacío.");
            if (!this.ubicacion) this.errorMostrarMsjArticulo.push("La ubicacion del artículo no puede estar vacío.");
            if (!this.stock) this.errorMostrarMsjArticulo.push("El stock del artículo debe ser un número y no puede estar vacío.");
            if (!this.origen) this.errorMostrarMsjArticulo.push("El origen del artículo no puede estar vacío.");
            if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;
            return this.errorArticulo;
        },
        registrarArticulo(){
            if (this.validarArticulo()){
                return;
            }
            let me = this;
            axios.post("/articulo/registrar",{
                'idcategoria': this.idcategoria_r,
                'codigo': this.codigo_r,
                'sku' : this.sku_r,
                'nombre': this.nombre_art,
                'terminado' : this.terminado,
                'largo' : this.largo,
                'alto' : this.alto,
                'metros_cuadrados' : this.metros_cuadrados,
                'espesor' : this.espesor,
                'ubicacion' : this.ubicacion,
                'stock': this.stock,
                'descripcion': this.descripcion,
                'observacion' : this.observacion,
                'origen' : this.origen,
                'fecha_llegada' : this.fecha_llegada,
                'file' : this.file
            }).then(function (response) {
                me.cerrarModal2();
                listarArticulo(buscarA,criterioA);
            }).catch(function (error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.listarIngreso(1,this.buscar, this.criterio);
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
    @media (min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>

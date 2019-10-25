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
          <i class="fa fa-align-justify"></i> Ventas
        </div>
        <!-- Listado -->
        <template v-if="listado==1">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                        <option value="num_comprobante">No° Comprobante</option>
                        <option value="fecha_hora">Fecha</option>
                        <option value="estado">Estado</option>
                        <option value="entregado">Entregado</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Atendió</th>
                                <th>Cliente</th>
                                <th>Tipo Comprobante</th>
                                <th>No° Comprobante</th>
                                <th>Fecha Hora</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                                <th>Forma de pago</th>
                                <th>Facturación</th>
                                <th>Entregado</th>
                                <th>100% Pagado</th>
                                <th>Estado</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVenta" :key="venta.id">
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" @click="verVenta(venta.id)">
                                        <i class="icon-eye"></i>
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-info btn-sm" @click="pdfVenta(venta.id)">
                                        <i class="icon-doc"></i>
                                    </button>&nbsp;
                                </td>
                                 <td v-text="venta.usuario"></td>
                                <td v-text="venta.nombre"></td>
                                <td v-text="venta.tipo_comprobante"></td>
                                <td v-text="venta.num_comprobante"></td>
                                <td v-text="venta.fecha_hora"></td>
                                <td v-text="venta.impuesto"></td>
                                <td v-text="venta.total"></td>
                                <td v-text="venta.forma_pago"></td>
                                <td v-text="venta.tipo_facturacion"></td>
                                <td v-if="venta.entregado">
                                    <toggle-button :value="true" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                </td>
                                <td v-else>
                                    <toggle-button :value="false" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                </td>
                                 <td v-if="venta.pagado">
                                    <toggle-button :value="true" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                </td>
                                <td v-else>
                                    <toggle-button :value="false" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                </td>
                                <td v-text="venta.estado "></td>
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

         <!-- Ver Venta detalle -->
        <template v-else-if="listado==2">
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Cliente</label>
                            <p v-text="cliente"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tipo Comprobante</label>
                            <p v-text="tipo_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Número de Comprobante</label>
                            <p v-text="num_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Registrado por:</label>
                            <p v-text="user"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Fecha:</label>
                            <p v-text="fecha_llegada"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Total:</label>
                            <p v-text="total"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Impuesto</label>
                            <p v-text="impuesto"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Moneda</label>
                            <p v-text="moneda"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tipo de cambio</label>
                            <p v-text="tipo_cambio"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tipo de Facturación</label>
                            <p v-text="tipo_facturacion"></p>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>Entregado:</strong> </label>
                            <div v-if="pagado == 1">
                                <toggle-button disabled v-model="btnEntrega" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}"/>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Pendiente de pago</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>100% Pagado: </strong> </label>
                            <div v-if="estadoVn == 'Registrado'">
                                <toggle-button disabled v-model="btnPagado" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Presupuesto cancelado</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Forma de pago</label>
                            <p v-text="forma_pago"></p>
                        </div>
                    </div>
                    <div class="col-md-2" v-if="forma_pago =='Cheque'">
                        <div class="form-group">
                            <label for=""><strong>No° de cheque</strong></label>
                            <p v-text="num_cheque"></p>
                        </div>
                    </div>
                    <div class="col-md-2" v-if="forma_pago =='Cheque'">
                        <div class="form-group">
                            <label for=""><strong>Banco</strong></label>
                            <p v-text="banco"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Detalles</th>
                                    <th>Código de material</th>
                                    <th>No° Placa</th>
                                    <th>Terminado</th>
                                    <th>Espesor</th>
                                    <th>largo</th>
                                    <th>Alto</th>
                                    <th>Metros <sup>2</sup></th>
                                    <th>Cantidad</th>
                                    <th>Precio m<sup>2</sup></th>
                                    <th>Descuento </th>
                                    <th>Ubicacion</th>
                                    <th>SubTotal</th>

                                </tr>
                            </thead>
                            <tbody v-if="arrayDetalle.length">
                                <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                    <td>
                                        <button type="button" @click="abrirModal3(index)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="detalle.sku"></td>
                                    <td v-text="detalle.codigo"></td>
                                    <td v-text="detalle.terminado"></td>
                                    <td v-text="detalle.espesor"></td>
                                    <td v-text="detalle.largo"></td>
                                    <td v-text="detalle.alto"></td>
                                    <td v-text="detalle.metros_cuadrados"></td>
                                    <td v-text="detalle.cantidad"></td>
                                    <td v-text="detalle.precio"></td>
                                    <td v-text="detalle.descuento"></td>
                                    <td v-text="detalle.ubicacion"></td>
                                    <td>{{ (( (detalle.precio * detalle.cantidad) * detalle.metros_cuadrados) - detalle.descuento) }}</td>
                                </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="12" align="right"><strong>Total Parcial:</strong></td>
                                    <td>$ {{total_parcial = (total / divImp).toFixed(2) }}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="12" align="right"><strong>Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto=((total * impuesto)/(divImp)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="12" align="right"><strong>Total Neto:</strong></td>
                                    <td>$ {{ total}} </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="13" class="text-center">
                                        <strong>NO hay artículos en este detalle...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="exampleFormControlTextarea2">Observaciones</label>
                        <textarea class="form-control rounded-0" rows="3" maxlength="256" readonly v-model="observacion"></textarea>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Lugar de entrega</label>
                            <p v-text="lugar_entrega"></p>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tiempo de entrega</label>
                            <p v-text="tiempo_entrega"></p>
                        </div>
                    </div>&nbsp;
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Fin ver Venta detalle-->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

    <!--Inicio del modal Visualizar articulo Insertado-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" data-spy="scroll"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-info modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal + sku"></h4>
            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <h1 class="text-center" v-text="sku"></h1>
                <lightbox class="m-0" album="" :src="'http://localhost:8000/images/'+file">
                    <img class="img-responsive imgcenter" width="500px" :src="'http://localhost:8000/images/'+file">
                </lightbox>&nbsp;
                <table class="table table-bordered table-striped table-sm text-center table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" colspan="2">Detalle del artículo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>NO° DE PLACA</strong></td>
                            <td v-text="codigo"></td>
                        </tr>
                        <tr>
                            <td><strong>MATERIAL</strong></td>
                            <td v-text="categoria"></td>
                        </tr>
                        <tr >
                            <td><strong>CODIGO DE MATERIAL</strong></td>
                            <td v-text="sku"></td>
                        </tr>
                        <tr >
                            <td><strong>TERMINADO</strong></td>
                            <td v-text="terminado"></td>
                        </tr>
                        <tr >
                            <td><strong>LARGO</strong></td>
                            <td v-text="largo"></td>
                        </tr>
                        <tr >
                            <td><strong>ALTO</strong></td>
                            <td v-text="alto"></td>
                        </tr>
                        <tr >
                            <td><strong>METROS<sup>2</sup> </strong></td>
                            <td v-text="calcularMts"></td>
                        </tr>
                        <tr >
                            <td><strong>ESPESOR</strong></td>
                            <td v-text="espesor"></td>
                        </tr>
                        <tr >
                            <td><strong>PRECIO</strong></td>
                            <td v-text="precio"></td>
                        </tr>
                        <tr >
                            <td><strong>BODEGA DE DESCARGA</strong></td>
                            <td v-text="ubicacion"></td>
                        </tr>
                        <tr >
                            <td><strong>Stock</strong></td>
                            <td v-text="stock"></td>
                        </tr>
                        <tr >
                            <td><strong>DESCRIPCION</strong></td>
                            <td v-text="descripcion"></td>
                        </tr>
                        <tr >
                            <td><strong>OBSERVACIONES</strong></td>
                            <td v-text="observacion"></td>
                        </tr>
                        <tr >
                            <td><strong>ORIGEN</strong></td>
                            <td v-text="origen"></td>
                        </tr>
                        <tr >
                            <td><strong>CONTENEDOR</strong></td>
                            <td v-text="contenedor"></td>
                        </tr>
                        <tr >
                            <td><strong>ESPESOR</strong></td>
                            <td v-text="espesor"></td>
                        </tr>
                        <tr >
                            <td><strong>FECHA DE LLEGADA</strong></td>
                            <td v-text="fecha_llegada"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <barcode :value="codigo" :options="{formar: 'EAN-13'}">
                            Sin código de barras.
                    </barcode>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal Visualizar articulo detalle listado==2-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal3}" data-spy="scroll"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-info modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal + sku"></h4>
            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <h1 class="text-center" v-text="sku"></h1>
                <lightbox class="m-0" album="" :src="'http://localhost:8000/images/'+file">
                    <img class="img-responsive imgcenter" width="500px" :src="'http://localhost:8000/images/'+file">
                </lightbox>&nbsp;
                <div v-if="condicion == 1" class="text-center">
                    <span class="badge badge-success">Activo</span>
                </div>
                <div v-else-if="condicion == 3" class="text-center">
                    <span class="badge badge-warning">Cortado</span>
                </div>
                <div v-else class="text-center">
                    <span class="badge badge-danger">Desactivado</span>
                </div>&nbsp;
                <table class="table table-bordered table-striped table-sm text-center table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" colspan="2">Detalle del artículo</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>NO° DE PLACA</strong></td>
                        <td v-text="codigo"></td>
                    </tr>
                    <tr>
                        <td><strong>MATERIAL</strong></td>

                        <select disabled class="form-control selectDetalle" v-model="idcategoria">
                            <option value="0" disabled>Seleccione un material</option>
                            <option class="text-center" v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                        </select>

                    </tr>
                    <tr >
                        <td><strong>CODIGO DE MATERIAL</strong></td>
                        <td v-text="sku"></td>
                    </tr>
                    <tr >
                        <td><strong>TERMINADO</strong></td>
                        <td v-text="terminado"></td>
                    </tr>
                    <tr >
                        <td><strong>LARGO</strong></td>
                        <td v-text="largo"></td>
                    </tr>
                    <tr >
                        <td><strong>ALTO</strong></td>
                        <td v-text="alto"></td>
                    </tr>
                    <tr >
                        <td><strong>METROS<sup>2</sup> </strong></td>
                        <td v-text="metros_cuadrados"></td>
                    </tr>
                    <tr >
                        <td><strong>ESPESOR</strong></td>
                        <td v-text="espesor"> </td>
                    </tr>
                    <tr >
                        <td><strong>PRECIO</strong></td>
                        <td v-text="precio"></td>
                    </tr>
                    <tr >
                        <td><strong>BODEGA DE DESCARGA</strong></td>
                        <td v-text="ubicacion"></td>
                    </tr>
                    <tr >
                        <td><strong>Cantidad</strong></td>
                        <td v-text="cantidad"></td>
                    </tr>
                    <tr >
                        <td><strong>DESCRIPCION</strong></td>
                        <td v-text="descripcion"></td>
                    </tr>
                    <tr >
                        <td><strong>OBSERVACIONES</strong></td>
                        <td v-text="observacion"></td>
                    </tr>
                    <tr >
                        <td><strong>ORIGEN</strong></td>
                        <td v-text="origen"></td>
                    </tr>
                    <tr >
                        <td><strong>CONTENEDOR</strong></td>
                        <td v-text="contenedor"></td>
                    </tr>
                    <tr >
                        <td><strong>ESPESOR</strong></td>
                        <td v-text="espesor"></td>
                    </tr>
                    <tr >
                        <td><strong>FECHA DE LLEGADA</strong></td>
                        <td v-text="fecha_llegada"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <barcode :value="codigo" :options="{formar: 'EAN-13'}">
                            Sin código de barras.
                    </barcode>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
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
import VueBarcode from 'vue-barcode';
import VueLightbox from 'vue-lightbox';
import moment from 'moment';
import ToggleButton from 'vue-js-toggle-button'
Vue.component("Lightbox",VueLightbox);
Vue.use(ToggleButton);
export default {
    data() {
        return {
            venta_id: 0,
            cliente: '',
            user: '',
            tipo_comprobante: "PRESUPUESTO",
            num_comprobante: "",
            impuesto: 0.16,
            descuento : 0,
            moneda : 'Peso Mexicano',
            tipo_cambio : 0,
            observacion : '',
            categoria : '',
            idarticulo : 0,
            articulo : "",
            codigo: "",
            idcategoria :0,
            sku : '',
            terminado : '',
            largo : 0,
            alto : 0,
            metros_cuadrados : 0,
            espesor : 0,
            ubicacion : '',
            origen : '',
            contenedor : '',
            fecha_llegada : '',
            file : '',
            imagenMinatura : '',
            arrayCategoria : [],
            condicion : 0,
            precio_venta : 0,
            cantidad : 0,
            total_impuesto : 0.0,
            total_parcial : 0.0,
            divImp: 0.0,
            total: 0.0,
            forma_pago : "De contado",
            tiempo_entrega : "",
            lugar_entrega : "",
            precio: 0.0,
            entregado : 0,
            tipo_facturacion : "",
            num_cheque : 0,
            banco : "",
            pagado : 0,
            stock : 0,
            descripcion : "",
            arrayVenta : [],
            arrayDetalle : [],
            listado : 1,
            modal: 0,
            modal2: 0,
            modal3: 0,
            ind : '',
            tituloModal: "",
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
            btnEntrega : false,
            btnPagado : false,
            estadoVn : ""
        };
    },
    components: {
        vSelect,
        'barcode': VueBarcode
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
            imagen(){
                return this.imagenMinatura;
            },
            calcularMts : function(){
                let me=this;
                let resultado = 0;
                resultado = resultado + (me.alto * me.largo);
                me.metros_cuadrados = resultado;
                return resultado;
            }

        },
    methods: {
        listarVenta (page,buscar,criterio){
            let me=this;
            var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayVenta = respuesta.ventas.data;
                me.pagination= respuesta.pagination;
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
                me.listarVenta(page,buscar,criterio);
        },
        mostrarDetalle(){
            this.listado = 0;
            this.codigo = "";
            this.idarticulo = 0;
            this.articulo = "";
            this.sku = "";
            this.idcategoria = 0;
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.terminado = '';
            this.espesor = 0;
            this.precio_venta = 0;
            this.precio_venta = 0;
            this.cantidad = 0;
            this.file = '';
            this.origen = '';
            this.contenedor = '';
            this.fecha_llegada = '';
            this.ubicacion = '';
            this.arrayDetalle = [];
            this.idproveedor = 0;
            this.num_comprobante = 0;
            this.tipo_facturacion = "";
            this.num_cheque = 0;
            this.banco = "";
            this.pagado = 0;
            this.selectCategoria();
        },
        ocultarDetalle(){
            this.listado = 1;
            this.codigo = "";
            this.idarticulo = 0;
            this.articulo = "";
            this.sku = "";
            this.idcategoria = 0;
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.terminado = '';
            this.espesor = 0;
            this.precio_venta = 0;
            this.precio = 0;
            this.cantidad = 0;
            this.file = '';
            this.origen = '';
            this.contenedor = '';
            this.fecha_llegada = '';
            this.ubicacion = '';
            this.moneda = 'Peso Mexicano';
            this.tipo_cambio = '0';
            this.stock = 0;
            this.cliente = 0;
            this.categoria = 0;
            this.observacion = "";
            this.arrayDetalle = [];
            this.num_comprobante = 0;
            this.entregado = 0;
            this.tipo_facturacion = "";
            this.num_cheque = 0;
            this.banco = "";
            this.pagado = 0;
            this.btnEntrega =  false;
            this.btnPagado = false;
        },
        verVenta(id){

            let me = this;
            me.listado = 2;

            //Obtener los datos del ingreso
            var arrayVentaT=[];
            var url= '/venta/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta= response.data;
                arrayVentaT = respuesta.venta;

                var fechaform  = arrayVentaT[0]['fecha_hora'];

                var total_parcial = 0;

                me.venta_id = arrayVentaT[0]['id'];
                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                me.user=arrayVentaT[0]['usuario'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
                me.forma_pago = arrayVentaT[0]['forma_pago'];
                me.lugar_entrega = arrayVentaT[0]['lugar_entrega'];
                me.tiempo_entrega = arrayVentaT[0]['tiempo_entrega'];
                me.entregado = arrayVentaT[0]['entregado'];
                me.moneda = arrayVentaT[0]['moneda'];
                me.tipo_cambio = arrayVentaT[0]['tipo_cambio'];
                me.observacion = arrayVentaT[0]['observacion'];
                me.estadoVn = arrayVentaT[0]['estado'];
                me.tipo_facturacion = arrayVentaT[0]['tipo_facturacion'];
                me.num_cheque = arrayVentaT[0]['num_cheque'];
                me.banco = arrayVentaT[0]['banco'];
                me.pagado = arrayVentaT[0]['pagado'];

                moment.locale('es');
                me.fecha_llegada=moment(fechaform).format('llll');

                 var imp =   parseFloat(me.impuesto = arrayVentaT[0]['impuesto']);

                me.divImp = imp + 1;

                if(me.entregado ==1){
                    me.btnEntrega = true;
                }

                if(me.pagado == 1){
                    me.btnPagado = true;
                }
            })
            .catch(function (error) {
                console.log(error);
            });

            //Obtener los detalles del ingreso
            var url= '/venta/obtenerDetalles?id=' + id;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayDetalle = respuesta.detalles;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        abrirModal2(index){
            let me = this;
            me.ind = index;
            me.modal2 = 1;
            me.tituloModal      = "Detalles Artículo ";
            me.sku              = me.arrayDetalle[index]['articulo'];
            me.codigo           = me.arrayDetalle[index]['codigo'];
            me.categoria        = me.arrayDetalle[index]['categoria'];
            me.largo            = me.arrayDetalle[index]['largo'];
            me.alto             = me.arrayDetalle[index]['alto'];
            me.ubicacion        = me.arrayDetalle[index]['ubicacion'];
            me.terminado        = me.arrayDetalle[index]['terminado'];
            me.espesor          = me.arrayDetalle[index]['espesor'];
            me.precio_venta     = me.arrayDetalle[index]['precio_venta'];
            me.metros_cuadrados = me.arrayDetalle[index]['metros_cuadrados'];
            me.contenedor       = me.arrayDetalle[index]['contenedor'];
            me.fecha_llegada    = me.arrayDetalle[index]['fecha_llegada'];
            me.origen           = me.arrayDetalle[index]['origen'];
            me.stock            = me.arrayDetalle[index]['stock'];
            me.file             = me.arrayDetalle[index]['file'];
            me.origen           = me.arrayDetalle[index]['origen'];
            me.contenedor       = me.arrayDetalle[index]['contenedor'];
            me.descripcion      = me.arrayDetalle[index]['descripcion'];
            me.observacion      = me.arrayDetalle[index]['observacion'];
            me.selectCategoria();
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.sku = '';
            this.codigo = '';
            this.categoria = 0;
            this.largo = 0;
            this.alto = 0;
            this.terminado = '';
            this.espesor = 0;
            this.ubicacion = '';
            this.precio_venta = 0;
            this.metros_cuadrados = 0;
            this.stock = 0;
            this.file = '';
            this.origen = '';
            this.observacion = '';
            this.contenedor = '';
            this.descripcion = '';
            this.ind = '';
            this.fecha_llegada = '';
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
        abrirModal3(index){
            let me = this;
            me.ind = index;
            me.modal3 = 1;
            me.tituloModal      = "Artículo ";
            me.sku              = me.arrayDetalle[index]['sku'];
            me.codigo           = me.arrayDetalle[index]['codigo'];
            me.idcategoria      = me.arrayDetalle[index]['idcategoria'];
            me.largo            = me.arrayDetalle[index]['largo'];
            me.alto             = me.arrayDetalle[index]['alto'];
            me.ubicacion        = me.arrayDetalle[index]['ubicacion'];
            me.terminado        = me.arrayDetalle[index]['terminado'];
            me.espesor          = me.arrayDetalle[index]['espesor'];
            me.precio           = me.arrayDetalle[index]['precio'];
            me.metros_cuadrados = me.arrayDetalle[index]['metros_cuadrados'];
            me.contenedor       = me.arrayDetalle[index]['contenedor'];
            me.fecha_llegada    = me.arrayDetalle[index]['fecha_llegada'];
            me.origen           = me.arrayDetalle[index]['origen'];
            me.cantidad         = me.arrayDetalle[index]['cantidad'];
            me.file             = me.arrayDetalle[index]['file'];
            me.descripcion      = me.arrayDetalle[index]['descripcion'];
            me.observacion      = me.arrayDetalle[index]['observacion'];
            me.condicion        = me.arrayDetalle[index]['condicion'];
            me.selectCategoria();
        },
        cerrarModal3() {
            this.modal3 = 0;
            this.sku = '';
            this.codigo = '';
            this.idcategoria = 0;
            this.largo = 0;
            this.alto = 0;
            this.terminado = '';
            this.espesor = 0;
            this.precio_venta = 0;
            this.metros_cuadrados = 0;
            this.stock = 0;
            this.file = '';
            this.descripcion = '';
            this.ind = '';
        },
        pdfVenta(id){
            window.open('http://127.0.0.1:8000/venta/pdf/'+id + ',' + '_blank');
        }
    },
    mounted() {
        this.listarVenta(1,this.buscar, this.criterio);
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
    .selectDetalle {
        background: white;
        border: none;
        text-align: center;

  }
</style>

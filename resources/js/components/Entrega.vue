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
          <i class="fa fa-align-justify"></i> Entregas
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
                        <option value="entregado">Entregado 100%</option>
                        <option value="entrega_parcial">Entrega Parcial</option>
                        <!-- <option value="forma_pago">Forma de pago</option> -->
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
                                <th>No° Comprobante</th>
                                <th>Fecha Hora</th>
                                <th>Total</th>
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
                                    <button type="button" class="btn btn-warning btn-sm" @click="entregarVenta(venta.id)">
                                        <i class="fa fa-truck"></i>
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click="pdfEntrega(venta.id)">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>&nbsp;
                                </td>
                                <td v-text="venta.usuario"></td>
                                <td v-text="venta.nombre"></td>
                                <td v-text="venta.num_comprobante"></td>
                                <td v-text="venta.fecha_hora"></td>
                                <td v-text="venta.total"></td>
                                <td v-text="venta.tipo_facturacion"></td>
                                <td v-if="venta.entregado">
                                    <span class="badge badge-success">100%</span>
                                </td>
                                <td v-else-if="venta.entrega_parcial">
                                    <span class="badge badge-warning">Parcial</span>
                                </td>
                                <td v-else>
                                     <span class="badge badge-danger">No entregado</span>
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

        <!-- Ver Entrega -->
        <template v-else-if="listado==2">
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for=""><strong>Cliente</strong></label>
                            <p v-text="cliente"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tipo Comprobante</strong></label>
                            <p v-text="tipo_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for=""><strong>Número de Comprobante</strong></label>
                            <p v-text="num_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Registrado por:</strong></label>
                            <p v-text="user"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Fecha:</strong></label>
                            <p v-text="fecha_llegada"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Total:</strong></label>
                            <p v-text="total"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tipo de facturación</strong></label>
                            <p v-text="tipo_facturacion"></p>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Forma de pago</strong></label>
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
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>Entregado 100%:</strong> </label>
                            <div v-if="pagado == 1">
                                <toggle-button @change="cambiarEstadoEntrega(venta_id)" v-model="btnEntrega" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <div v-else-if="estadoVn == 'Registrado'">
                                <span class="badge badge-danger">Pendiente de pago</span>
                            </div>
                            <div v-else></div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>Entregado Parcial:</strong> </label>
                            <div v-if="pagado == 1">
                                <toggle-button @change="cambiarEstadoEntregaParcial(venta_id)" v-model="btnEntregaParcial" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <div v-else-if="estadoVn == 'Registrado'">
                                <span class="badge badge-danger">Pendiente de pago</span>
                            </div>
                            <div v-else></div>
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
                                    <th>Ubicacion</th>
                                    <th>Cantidad</th>
                                    <th>Por entregar</th>
                                    <th>Entregadas</th>
                                    <th>Pendientes</th>
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
                                    <td v-text="detalle.ubicacion"></td>
                                    <td v-text="detalle.cantidad"></td>
                                    <td v-text="detalle.por_entregar"></td>
                                    <td v-text="detalle.entregadas"></td>
                                    <td v-text="detalle.pendientes"></td>
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
                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlTextarea2"><strong>Observaciones</strong></label>
                            </div>
                            <div class="col-2">
                                <template v-if="obsEditable == 0">
                                    <button type="button" class="btn btn-warning btn-sm float-right" @click="editObservacion()">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-primary btn-sm float-right" @click="actualizarObservacion(venta_id)">
                                        <i class="fa fa-floppy-o"></i>
                                    </button>
                                </template>&nbsp;

                            </div>
                        </div>&nbsp;
                        <template v-if="obsEditable == 0">
                            <textarea class="form-control rounded-0" rows="3" maxlength="256" readonly v-model="observacion"></textarea>
                        </template>
                        <template v-else>
                            <textarea class="form-control rounded-0" rows="3" maxlength="256" v-model="observacion"></textarea>
                        </template>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Lugar de entrega</strong></label>
                            <p v-text="lugar_entrega"></p>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tiempo de entrega</strong></label>
                            <p v-text="tiempo_entrega"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <template v-if="imagenMinatura !='entregas/null'">
                            <!-- <lightbox class="m-0" album="" :src="'http://inventariostroystone.com/entregas/'+fileventa"> -->
                            <lightbox class="m-0" album="" :src="'entregas/'+fileventa">
                                <img alt="Sin imagen" class="img-responsive img-fluid imgcenter" width="800px" height="300px" :src="'/entregas/'+fileventa">
                            </lightbox>
                        </template>
                    </div>&nbsp;
                    <div class="col-md-1 mr-5 p-0 m-0" v-if="showElim">
                        <button type="button" class="btn btn-danger btn-circle float-left" aria-label="Eliminar imagen" @click="eliminarImagen(venta_id)">
                            <i class="fa fa-times"></i>
                        </button>&nbsp;
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Fin ver Entrega-->
        <!-- Actualizar Entrega -->
        <template v-else-if="listado==3">
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for=""><strong>Cliente</strong></label>
                            <p v-text="cliente"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tipo Comprobante</strong></label>
                            <p v-text="tipo_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for=""><strong>Número de Comprobante</strong></label>
                            <p v-text="num_comprobante"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Registrado por:</strong></label>
                            <p v-text="user"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Fecha:</strong></label>
                            <p v-text="fecha_llegada"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Total:</strong></label>
                            <p v-text="total"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tipo de facturación</strong></label>
                            <p v-text="tipo_facturacion"></p>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Forma de pago</strong></label>
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
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>Entregado 100%:</strong> </label>
                            <div v-if="pagado == 1">
                                <toggle-button @change="cambiarEstadoEntrega(venta_id)" v-model="btnEntrega" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <div v-else-if="estadoVn == 'Registrado'">
                                <span class="badge badge-danger">Pendiente de pago</span>
                            </div>
                            <div v-else></div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for=""><strong>Entregado Parcial:</strong> </label>
                            <div v-if="pagado == 1">
                                <toggle-button @change="cambiarEstadoEntregaParcial(venta_id)" v-model="btnEntregaParcial" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <div v-else-if="estadoVn == 'Registrado'">
                                <span class="badge badge-danger">Pendiente de pago</span>
                            </div>
                            <div v-else></div>
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
                                    <th>Ubicacion</th>
                                    <th>Cantidad</th>
                                    <th>Entregadas</th>
                                    <th>Pendientes</th>
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
                                    <td v-text="detalle.ubicacion"></td>
                                    <td v-text="detalle.cantidad"></td>
                                    <td>
                                        <span style="color:red;" v-show="detalle.entregadas>detalle.cantidad">Solo dede entregar: {{detalle.por_entregar}}</span>
                                        <input v-model="detalle.entregadas" min="1" type="number" class="form-control">
                                    </td>
                                    <td v-text="detalle.pendientes"></td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="13" class="text-center">
                                        <strong>NO hay artículos pendientes de entrega...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlTextarea2"><strong>Observaciones</strong></label>
                            </div>
                            <div class="col-2">
                                <template v-if="obsEditable == 0">
                                    <button type="button" class="btn btn-warning btn-sm float-right btn-circle" @click="editObservacion()">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-primary btn-sm float-right btn-circle" @click="actualizarObservacion(venta_id)">
                                        <i class="fa fa-floppy-o"></i>
                                    </button>
                                </template>&nbsp;

                            </div>
                        </div>&nbsp;
                        <template v-if="obsEditable == 0">
                            <textarea class="form-control rounded-0" rows="3" maxlength="256" readonly v-model="observacion"></textarea>
                        </template>
                        <template v-else>
                            <textarea class="form-control rounded-0" rows="3" maxlength="256" v-model="observacion"></textarea>
                        </template>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Lugar de entrega</strong></label>
                            <p v-text="lugar_entrega"></p>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><strong>Tiempo de entrega</strong></label>
                            <p v-text="tiempo_entrega"></p>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2">
                        <template v-if="imagenMinatura !='entregas/null'">
                            <lightbox class="m-0" album="" :src="imagen">
                                <figure>
                                    <img width="300" height="200" class="img-responsive img-fluid imgcenter" :src="imagen" alt="Foto del artículo">
                                </figure>
                            </lightbox>&nbsp;
                        </template>
                    </div>
                    <div class="col-md-1 mr-5 p-0 m-0" v-if="showElim">
                        <button type="button" class="btn btn-danger btn-circle float-left" aria-label="Eliminar imagen" @click="eliminarImagen(venta_id)">
                            <i class="fa fa-times"></i>
                        </button>&nbsp;
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-8 order-md-1 order-2">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>&nbsp;
                        <button type="button" class="btn btn-primary" @click="actualizarDetalle()">Actualizar</button>&nbsp;
                    </div>
                    <div class="col-md-4 order-md-2 order-1 float-right">
                        <div class="form-group row">
                            <label class="col form-control-label" for="text-input"> <strong>Actualizar Imagen</strong></label>&nbsp;
                            <div class="col">
                                <input type="file" :src="imagen" @change="obtenerImagen" class="form-control-file">&nbsp;
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-sm btn-primary btn-circle" @click="updImage()">
                                    <i class="fa fa-floppy-o"></i>
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <div class="col">1</div>
                    <div class="col">2</div>
                    <div class="col">3</div>
                    <div class="col">4</div>
                    <div class="col">5</div>
                    <div class="col">6</div>
                    <div class="col">7</div>
                    <div class="col">8</div>
                    <div class="col">9</div>
                    <div class="col">10</div>
                    <div class="col">11</div>
                    <div class="col">12</div>
                </div> -->
            </div>
        </template>
        <!-- Fin Actualizar Entrega-->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>


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
                <template v-if="file">
                    <lightbox class="m-0" album="" :src="'http://inventariostroystone.com/images/'+file">
                        <img class="img-responsive imgcenter" width="500px" :src="'http://inventariostroystone.com/images/'+file">
                    </lightbox>&nbsp;
                </template>
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
            idcliente: 0,
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
            forma_pago : "Efectivo",
            tiempo_entrega : "",
            lugar_entrega : "",
            precio: 0.0,
            entregado : 0,
            entregado_parcial: 0,
            stock : 0,
            descripcion : "",
            tipo_facturacion : "",
            num_cheque : 0,
            banco : "",
            pagado : 0,
            arrayArticulo : [],
            arrayVenta : [],
            arrayDetalle : [],
            arrayCliente : [],
            listado : 1,
            modal3: 0,
            ind : '',
            tituloModal: "",
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
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
            criterioA : 'sku',
            codigoA : "",
            codigoB : "",
            largoA : 0,
            largoB : 0,
            altoA : 0,
            altoB : 0,
            metros_cuadradosA : 0,
            metros_cuadradosB : 0,
            precioA : 0,
            precioB : 0,
            ubicacionA : "",
            ubicacionB : "",
            validatedB : 0,
            validatedA : 0,
            btnEntrega : false,
            btnEntregaParcial : false,
            btnPagado : false,
            estadoVn : "",
            CodeDate : "",
            obsEditable : 0,
            sigNum : 0,
            por_entregar : 0,
            entregadas : 0,
            pendientes : 0,
            entregasComp : 0,
            fileventa: "",
            showElim : false
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
            calcularTotal : function(){
                let me=this;
                let resultado = 0;
                for(var i=0;i<me.arrayDetalle.length;i++){
                    resultado = resultado + (
                        (

                            ((((me.arrayDetalle[i].precio * me.arrayDetalle[i].metros_cuadrados) * me.arrayDetalle[i].cantidad)) - me.arrayDetalle[i].descuento) * (me.impuesto + 1))

                        )
                }
                return resultado;
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
            },
            calcularMtsA : function(){
                let me = this;
                let resultado = 0;
                resultado = resultado + (me.altoA * me.largoA);
                me.metros_cuadradosA = resultado;
                return resultado;
            },
            calcularMtsB : function(){
                let me=this;
                let resultado = 0;
                resultado = resultado + (me.altoB * me.largoB);
                me.metros_cuadradosB = resultado;
                return resultado;
            },
            calcularMtsRestantes : function(){
                let me=this;
                let resultado = 0;
                resultado = me.metros_cuadrados - (me.metros_cuadradosA + me.metros_cuadradosB);
                return resultado;
            },
            getFechaCode : function(){
                let me = this;
                let date = "";
                moment.locale('es');
                date = moment().format('YYMMDD');
                me.CodeDate = moment().format('YYMMDD');
                return date;
            },
            imagen(){
                return this.imagenMinatura;
            }
        },
    methods: {
        listarVenta (page,buscar,criterio){
            let me=this;

            /* if(me.criterio == 'entregado'){
                me.buscar = 1;
            } */

            var url= '/entrega?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
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
            this.errorVenta =0;
            this.errorMostrarMsjVenta = [];
            this.num_comprobante = 0;
            this.entregado = 0;
            this.btnEntrega =  false;
            this.btnEntregaParcial = false;
            this.btnPagado = false;
            this.obsEditable = 0;
            this.entregasComp = 0;
            this.fileventa = "";
            this.imagenMinatura = "";
            this.showElim = false;
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
                me.entregado_parcial = arrayVentaT[0]['entrega_parcial'];
                me.moneda = arrayVentaT[0]['moneda'];
                me.tipo_cambio = arrayVentaT[0]['tipo_cambio'];
                me.observacion = arrayVentaT[0]['observacion'];
                me.estadoVn = arrayVentaT[0]['estado'];
                me.num_cheque = arrayVentaT[0]['num_cheque'];
                me.banco = arrayVentaT[0]['banco'];
                me.tipo_facturacion = arrayVentaT[0]['tipo_facturacion'];
                me.pagado = arrayVentaT[0]['pagado'];
                me.fileventa = arrayVentaT[0]['file'];


                moment.locale('es');
                me.fecha_llegada=moment(fechaform).format('llll');

                var imp =   parseFloat(me.impuesto = arrayVentaT[0]['impuesto']);

                me.divImp = imp + 1;

                let hasImg = 'entregas/' + arrayVentaT[0]['file'];

                if(hasImg != 'entregas/null'){
                    me.imagenMinatura = '/entregas/'+ arrayVentaT[0]['file'];
                    me.showElim = true;
                    console.log('Elim: '+me.showElim);
                }else{
                    me.imagenMinatura = 'entregas/null';
                    me.showElim = false;
                    console.log('Elim: '+me.showElim);
                }

                if(me.entregado ==1){
                    me.btnEntrega = true;
                    me.btnEntregaParcial = false;
                }

                if(me.entregado_parcial ==1){
                    me.btnEntregaParcial = true;
                    me.btnEntrega = false;
                }

                if(me.pagado ==1){
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
            me.categoria        = me.arrayDetalle[index]['categoria'];
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
            this.categoria = '';
        },
        cambiarEstadoEntrega(id){
            let me = this;
            if(me.btnEntrega == true){
                me.entregado = 1;
            }else{
                me.entregado = 0;
            }
            axios.post('/venta/cambiarEntrega',{
                'id': id,
                'entregado' : this.entregado
            }).then(function (response) {
                me.listarVenta(1,'','num_comprobante');
            }).catch(function (error) {
                console.log(error);
            });
        },
        cambiarEstadoEntregaParcial(id){
            let me = this;
            if(me.btnEntregaParcial == true){
                me.entregado_parcial = 1;
            }else{
                me.entregado_parcial = 0;
            }
            axios.post('/venta/cambiarEntregaParcial',{
                'id': id,
                'entrega_parcial' : this.entregado_parcial
            }).then(function (response) {
                me.listarVenta(1,'','num_comprobante');
            }).catch(function (error) {
                console.log(error);
            });
        },
        editObservacion(){
            let me = this;
            me.obsEditable = 1;
        },
        actualizarObservacion(id){
            let me = this;
            axios.post('/venta/actualizarObservacion',{
                'id': id,
                'observacion' : this.observacion
            }).then(function (response) {
                me.obsEditable = 0;
            }).catch(function (error) {
                console.log(error);
            });
        },
        pdfEntrega(id){
            window.open('/entrega/pdf/'+id,'_blank');
        },
        entregarVenta(id){
            let me = this;
            me.listado = 3;

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
                me.entregado_parcial = arrayVentaT[0]['entrega_parcial'];
                me.moneda = arrayVentaT[0]['moneda'];
                me.tipo_cambio = arrayVentaT[0]['tipo_cambio'];
                me.observacion = arrayVentaT[0]['observacion'];
                me.estadoVn = arrayVentaT[0]['estado'];
                me.num_cheque = arrayVentaT[0]['num_cheque'];
                me.banco = arrayVentaT[0]['banco'];
                me.tipo_facturacion = arrayVentaT[0]['tipo_facturacion'];
                me.pagado = arrayVentaT[0]['pagado'];
                me.fileventa = arrayVentaT[0]['file'];
                /* me.imagenMinatura = '/entregas/'+ arrayVentaT[0]['file']; */
                /* me.imagenMinatura = 'http://inventariostroystone.com/entregas/'+ arrayVentaT[0]['file']; */

                moment.locale('es');
                me.fecha_llegada=moment(fechaform).format('llll');

                 var imp =   parseFloat(me.impuesto = arrayVentaT[0]['impuesto']);

                me.divImp = imp + 1;

                let hasImg = 'entregas/' + arrayVentaT[0]['file'];

                /* console.log("HasImg: " + hasImg); */

                if(hasImg != 'entregas/null'){
                    me.imagenMinatura = '/entregas/'+ arrayVentaT[0]['file'];
                    me.showElim = true;
                }else{

                    me.imagenMinatura = 'entregas/null';
                    me.showElim = false;
                }

                if(me.entregado ==1){
                    me.btnEntrega = true;
                    me.btnEntregaParcial = false;
                }

               if(me.entregado_parcial ==1){
                    me.btnEntregaParcial = true;
                    me.btnEntrega = false;
                }

                if(me.pagado ==1){
                    me.btnPagado = true;
                }
            })
            .catch(function (error) {
                console.log(error);
            });

            //Obtener los detalles del ingreso
            var url= '/venta/obtenerDetallesEntrega?id=' + id;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayDetalle = respuesta.detalles;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        actualizarDetalle(){
            let me = this;
            axios.put('/entrega/updDetalle',{
                'data': this.arrayDetalle
            }).then(function(response) {
                me.ocultarDetalle();
                me.listarVenta(1,'','num_comprobante');
                me.idcliente = 0;
                me.tipo_comprobante = "Presupuesto";
                me.num_comprobante = 0;
                me.impuesto = 0.16;
                me.total = 0.0;
                me.idarticulo = 0;
                me.articulo = "";
                me.cantidad = 0;
                me.precio = 0;
                me.stock = 0;
                me.observacion = "";
                me.descuento = 0;
                me.forma_pago = "Efectivo";
                me.tiempo_entrega = "";
                me.lugar_entrega = "";
                me.entregado = 0;
                me.entregado_parcial = 0;
                me.moneda = "Peso Mexicano";
                me.banco = "";
                me.num_cheque = 0;
                me.tipo_facturacion = "";
                me.tipo_cambio = "";
                me.arrayDetalle = [];

            })
            .catch(function(error) {
                console.log(error);
            });
        },
        obtenerImagen(e){
            let img = e.target.files[0];
            this.fileventa = img;
            this.cargarImagen(img);
        },
        cargarImagen(img){
            let reader = new FileReader();
            reader.onload = (e) => {
                this.imagenMinatura = e.target.result;
                this.fileventa =  e.target.result;
            }
            reader.readAsDataURL(img);
        },
        updImage(){
             let me = this;
            axios.put('/entrega/updImagen',{
                'file': this.fileventa,
                'id' : this.venta_id
            }).then(function(response) {
                Swal.fire({
                    type: 'success',
                    title: 'Completado...',
                    text: 'La imagen ha sido actualizada!',
                })
                me.ocultarDetalle();
                me.listarVenta(1,'','num_comprobante');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        eliminarImagen(id){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta de eliminar la imagen de esta entrega?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    /* console.log("id: " + id + " IMG: " + file); */
                    let me = this;
                    axios.put('/entrega/eliminarImg', {
                        'id' : id
                    }).then(function(response) {
                        me.listarVenta(1,'','num_comprobante');
                        me.imagenMinatura = 'images/null';
                        swalWithBootstrapButtons.fire(
                            "Elimada!",
                            "La imagen ha sido eliminada con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
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

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
          <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
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
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Cliente</th>
                                <th>Tipo Comprobante</th>
                                <th>No° Comprobante</th>
                                <th>Fecha Hora</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                                <th>Moneda</th>
                                <th>Tipo Cambio</th>
                                <th>Estado</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVenta" :key="venta.id">
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" @click="verVenta(venta.id)">
                                        <i class="icon-eye"></i>
                                    </button>&nbsp;
                                    <template v-if="venta.estado=='Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="venta.usuario"></td>
                                <td v-text="venta.nombre"></td>
                                <td v-text="venta.tipo_comprobante"></td>
                                <td v-text="venta.num_comprobante"></td>
                                <td v-text="venta.fecha_hora"></td>
                                <td v-text="venta.impuesto"></td>
                                <td v-text="venta.total"></td>
                                <td v-text="venta.moneda"></td>
                                <td v-text="venta.tipo_cambio"></td>
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

        <!-- Detalle -->
        <template v-else-if="listado==0">
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Cliente (*)</label>
                                <v-select
                                    :on-search="selectCliente"
                                    label="nombre"
                                    :options="arrayCliente"
                                    placeholder="Buscar clientes..."
                                    :onChange="getDatosCliente"
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Moneda<span style="color:red;" v-show="moneda==''">(*Seleccione)</span></label>
                            <select class="form-control" v-model="moneda">
                                <option value='' disabled>Seleccione la moneda del cobro</option>
                                <option value="Peso Mexicano">Peso Mexicano</option>
                                <option value="Dolar USA">Dolar USA</option>
                                <option value="EURO">EURO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Tipo cambio<span style="color:red;" v-show="moneda!='Peso Mexicano'">(*Seleccione)</span> </label>
                            <input type="text" class="form-control" v-model="tipo_cambio" placeholder="000xx">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div v-show="errorVenta" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row border">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Articulo <span style="color:red;" v-show="articulo==''">(*Seleccione)</span> </label>
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
                            <input type="number" min="0" value="0"  class="form-control" v-model="cantidad">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Precio <span style="color:red;" v-show="precio==0">(*Ingrese el precio)</span></label>
                            <input type="number" min="0" value="0" step="any" class="form-control" v-model="precio">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Descuento (%)</label>
                            <input type="number" min="0" value="0" class="form-control" v-model="descuento">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
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
                                    <th>Material</th>
                                    <th>Código de material</th>
                                    <th>No° Placa</th>
                                    <th>Terminado</th>
                                    <th>Espesor</th>
                                    <th>largo</th>
                                    <th>Alto</th>
                                    <th>Metros <sup>2</sup></th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Descuento </th>
                                    <th>Ubicacion</th>
                                    <th>SubTotal</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayDetalle.length">
                                <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                    <td>
                                        <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-close"></i>
                                        </button>&nbsp;
                                        <button type="button" @click="abrirModal2(index)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="detalle.categoria"></td>
                                    <td v-text="detalle.articulo"></td>
                                    <td v-text="detalle.codigo"></td>
                                    <td v-text="detalle.terminado"></td>
                                    <td v-text="detalle.espesor"></td>
                                    <td v-text="detalle.largo"></td>
                                    <td v-text="detalle.alto"></td>
                                    <td v-text="detalle.metros_cuadrados"></td>
                                    <td>
                                        <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Solo hay: {{detalle.stock}} disponibles</span>
                                        <input v-model="detalle.cantidad" min="1" type="number" class="form-control">
                                    </td>
                                    <td v-text="detalle.precio">
                                        <!-- <input v-model="detalle.precio" min="0" type="number" class="form-control"> -->
                                    </td>
                                    <td>
                                        <span style="color:red" v-show="detalle.descuento>(detalle.precio * detalle.cantidad)">Descuento superior al subtotal!</span>
                                        <input v-model="detalle.descuento" min="0" step="any" type="number" class="form-control">
                                    </td>
                                    <td v-text="detalle.ubicacion"></td>
                                    <td>
                                       {{ (detalle.precio * detalle.cantidad) - detalle.descuento }}
                                    </td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="13" align="right"><strong>Total Parcial:</strong></td>
                                    <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="13" align="right"><strong>Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto=((total * impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="13" align="right"><strong>Total Neto:</strong></td>
                                    <td>$ {{total=calcularTotal}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <strong>NO hay artículos agregados...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="exampleFormControlTextarea2">Observaciones</label>
                        <textarea class="form-control rounded-0" rows="3" maxlength="256" v-model="observacion"></textarea>
                    </div>&nbsp;
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Fin detalle -->
         <!-- Ver ingreso -->
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
                    <div class="col-md-3">
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
                </div>
                <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
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
                                    <th>Precio</th>
                                    <th>Descuento </th>
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
                                    <td>{{ (detalle.precio * detalle.cantidad) - detalle.descuento }}</td>
                                </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="11" align="right"><strong>Total Parcial:</strong></td>
                                    <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="11" align="right"><strong>Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto=((total * impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="11" align="right"><strong>Total Neto:</strong></td>
                                    <td>$ {{total=calcularTotal}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="12" class="text-center">
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
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Fin ver ingreso-->
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
                                    <option value="sku">Código de material</option>
                                    <option value="codigo">No° de placa</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead>
                            <tr class="text-center">
                                <th>Opciones</th>
                                <th>No° Placa</th>
                                <th>Código de material</th>
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
                                <td v-text="articulo.codigo"></td>
                                <td v-text="articulo.sku"></td>
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

    <!--Inicio del modal Visualizar articulo Insertado-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" data-spy="scroll"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-warning modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal + sku"></h4>
            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <h1 class="text-center" v-text="sku"></h1>
                <lightbox class="m-0" album="" :src="file">
                    <img class="img-responsive imgcenter" width="500px" :src="file">
                </lightbox>&nbsp;
                <div class="text-center">
                    <label class="text-left" for=""><strong>Actualizar Imagen</strong></label>
                     <input type="file" :src="imagen" @change="obtenerImagen" class="form-control-file">
                </div>
                <table class="table table-bordered table-striped table-sm text-center">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" colspan="2">Detalle del artículo</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>NO° DE PLACA</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="codigo" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>MATERIAL</strong></td>
                        <td>
                            <select class="form-control" v-model="idcategoria">
                                <option value="0" disabled>Seleccione un material</option>
                                <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                            </select>
                        </td>
                    </tr>
                    <tr >
                        <td><strong>CODIGO DE MATERIAL</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="sku">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>TERMINADO</strong></td>
                        <td>
                            <select class="form-control" v-model="terminado">
                                <option value='' disabled>Seleccione un de terminado</option>
                                <option value="Pulido">Pulido</option>
                                <option value="Al Corte">Al Corte</option>
                                <option value="Leather">Leather</option>
                                <option value="Mate">Mate</option>
                                <option value="Seda">Seda</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </td>
                    </tr>
                    <tr >
                        <td><strong>LARGO</strong></td>
                        <td>
                            <input type="number" min="0" value="0" step="any" class="form-control" v-model="largo">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>ALTO</strong></td>
                        <td>
                            <input type="number" min="0" value="0" step="any" class="form-control" v-model="alto">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>METROS<sup>2</sup> </strong></td>
                        <td>
                             <input type="number" readonly :value="calcularMts" class="form-control"/>
                        </td>
                    </tr>
                    <tr >
                        <td><strong>ESPESOR</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="espesor">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>PRECIO</strong></td>
                        <td>
                            <input type="number" min="0" value="0" class="form-control" v-model="precio_venta">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>BODEGA DE DESCARGA</strong></td>
                        <td>
                            <select class="form-control" v-model="ubicacion">
                                <option value='' disabled>Seleccione una bodega de descarga</option>
                                <option value="Del Musico">Del Músico</option>
                                <option value="Del Escultor">Escultor</option>
                                <option value="Del Sastre">Sastre</option>
                                <option value="Mecanicos">Mecánicos</option>
                                <option value="Tractorista">Tractorista</option>
                                <option value="San Luis">San Luis</option>
                            </select>
                        </td>
                    </tr>
                    <tr >
                        <td><strong>Cantidad</strong></td>
                        <td>
                             <input type="number" min="0" value="0" step="any" class="form-control" v-model="cantidad">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>DESCRIPCION</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="descripcion_r">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>OBSERVACIONES</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="observacion_r">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>ORIGEN</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="origen">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>CONTENEDOR</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="contenedor">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>ESPESOR</strong></td>
                        <td>
                            <input type="text" class="form-control" v-model="espesor">
                        </td>
                    </tr>
                    <tr >
                        <td><strong>FECHA DE LLEGADA</strong></td>
                        <td>
                            <input type="date" v-model="fecha_llegada" class="form-control" placeholder="Fecha de llegada"/>
                        </td>
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
            <button type="button" class="btn btn-ligth" @click="actualizarDetalle()">Actualizar</button>
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
                <lightbox class="m-0" album="" :src="'http://localhost:8000/'+file">
                    <img class="img-responsive imgcenter" width="500px" :src="'http://localhost:8000/'+file">
                </lightbox>&nbsp;
                <div v-if="condicion" class="text-center">
                    <span class="badge badge-success">Activo</span>
                </div>
                <div v-else class="text-center">
                    <span class="badge badge-danger">Desactivado</span>
                </div>&nbsp;
                <table class="table table-bordered table-striped table-sm text-center">
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
                        <td v-text="descripcion_r"></td>
                    </tr>
                    <tr >
                        <td><strong>OBSERVACIONES</strong></td>
                        <td v-text="observacion_r"></td>
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
Vue.component("Lightbox",VueLightbox);
export default {
    data() {
        return {
            venta_id: 0,
            idcliente: 0,
            cliente: '',
            user: '',
            tipo_comprobante: "FACTURA",
            num_comprobante: "",
            impuesto: 0.16,
            totalImpuesto : 0,
            totalParcial : 0,
            descuento : 0,
            moneda : 'Peso Mexicano',
            tipo_cambio : 0,
            observacion : '',
            categoria : '',
            idarticulo : 0,
            articulo : "",
            codigo: "",
            condicion : 0,
            precio_venta : 0,
            cantidad : 0,
            total_impuesto : 0.0,
            total_parcial : 0.0,
            total: 0.0,
            precio: 0.0,
            stock : 0,
            arrayArticulo : [],
            arrayVenta : [],
            arrayDetalle : [],
            arrayCliente : [],
            listado : 1,
            modal: 0,
            modal2: 0,
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

            //Registrar artículo
            articulo_idr: 0,
            idcategoria :0,
            nombre_categoria_r : '',
            codigo_r : '',
            sku : '',
            nombre_art: '',
            terminado : '',
            largo : 0,
            alto : 0,
            metros_cuadrados : 0,
            espesor : 0,
            ubicacion : '',

            descripcion_r: '',
            observacion_r : '',
            origen : '',
            contenedor : '',
            fecha_llegada : '',
            file : '',
            imagenMinatura : '',
            arrayArticulo_r: [],
            errorArticulo: 0,
            errorMostrarMsjArticulo: [],
            arrayCategoria : [],
            arryCodigos : [],
            fecha_conv : ''

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
                    resultado = resultado + ((me.arrayDetalle[i].precio * me.arrayDetalle[i].cantidad) - me.arrayDetalle[i].descuento)
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
        },
        buscarArticulo(){
            let me = this;
            var url= '/articulo/buscarArticuloVenta?filtro='+ me.codigo;

            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arrayArticulo=respuesta.articulos;

                if(me.arrayArticulo.length > 0){
                    me.articulo = me.arrayArticulo[0]['sku'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
                    me.precio = me.arrayArticulo[0]['precio_venta'];
                    me.stock = me.arrayArticulo[0]['stock'];
                    me.espesor = me.arrayArticulo[0]['espesor'];
                    me.largo = me.arrayArticulo[0]['largo'];
                    me.alto = me.arrayArticulo[0]['alto'];
                    me.metros_cuadrados = me.arrayArticulo[0]['metros_cuadrados'];
                    me.terminado =  me.arrayArticulo[0]['terminado'];
                    me.ubicacion =  me.arrayArticulo[0]['ubicacion'];
                    me.categoria = me.arrayArticulo[0]['nombre_categoria'];
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
                me.listarVenta(page,buscar,criterio);
        },
        encuentra(id){
            var sw=0;
            for(var i=0;i<this.arrayDetalle.length;i++){
                if(this.arrayDetalle[i].idarticulo==id){
                    sw=true;
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
            if(me.idarticulo == 0 || me.precio == 0 || me.cantidad == 0){
            }else{
                if(me.encuentra(me.idarticulo)){
                    Swal.fire({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este No° de placa ya esta en el listado!',
                    })
                    me.codigo = "";
                    me.sku = "";
                    me.idarticulo = "";
                    me.articulo="";
                    me.cantidad = 0;
                    me.precio = 0;
                    me.descuento = 0;
                    me.idcategoria = 0;
                    me.largo = 0;
                    me.alto = 0;
                    me.metros_cuadrados = 0;
                    me.terminado = 0;
                    me.espesor = 0;
                    me.stock = 0;
                    me.ubicacion = "";
                    me.categoria = "";

                }else{
                    if(me.cantidad > me.stock){
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'La cantidad excede las placas disponibles de este material!',
                        });
                    }else{
                        me.arrayDetalle.push({
                            idarticulo       : me.idarticulo,
                            articulo         : me.articulo,
                            sku              : me.sku,
                            codigo           : me.codigo,
                            idcategoria      : me.idcategoria,
                            largo            : me.largo,
                            alto             : me.alto,
                            metros_cuadrados : me.metros_cuadrados,
                            terminado        : me.terminado,
                            espesor          : me.espesor,
                            precio           : me.precio,
                            cantidad         : me.cantidad,
                            stock            : me.stock,
                            ubicacion        : me.ubicacion,
                            descuento        : me.descuento,
                            categoria        : me.categoria
                        });
                        me.codigo = "";
                        me.sku = "";
                        me.idarticulo = "";
                        me.articulo="";
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.idcategoria = 0;
                        me.largo = 0;
                        me.alto = 0;
                        me.metros_cuadrados = 0;
                        me.terminado = 0;
                        me.espesor = 0;
                        me.stock = 0;
                        me.ubicacion = "";
                        me.categoria = "";
                        me.observacion = "";
                    }
                }
            }
        },
        actualizarDetalle(){
            let me = this;
            if(me.codigo == 0 || me.precio_venta == 0 || me.cantidad == 0 || me.idcategoria == 0
               || me.alto == 0 || me.largo == 0){
            }else{
                me.eliminarDetalle(me.ind);
                me.arrayDetalle.push({
                    contenedor       : me.contenedor,
                    fecha_llegada    : me.fecha_llegada,
                    origen           : me.origen,
                    ubicacion        : me.ubicacion,
                    articulo         : me.articulo,
                    sku              : me.sku,
                    codigo           : me.codigo,
                    idcategoria      : me.idcategoria,
                    largo            : me.largo,
                    alto             : me.alto,
                    metros_cuadrados : me.metros_cuadrados,
                    terminado        : me.terminado,
                    espesor          : me.espesor,
                    precio_venta     : me.precio_venta,
                    cantidad         : me.cantidad,
                    stock            : me.cantidad,
                    imagen           : me.file,
                    descripcion      : me.descripcion_r,
                    observacion      : me.observacion_r
                });
                me.codigo = "";
                me.modal2 = 0;

            }
        },
        registrarArticulos() {
            let me = this;

            axios.post("/articulo/registrarDetalle", {
                'data' : this.arrayDetalle
            })
            .then(function(response) {
                me.registrarIngreso();
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        registrarVenta(){

            if (this.validarVenta()) {
                return;
            }
            let me = this;
            axios.post('/venta/registrar',{
                'idcliente': this.idcliente,
                'tipo_comprobante': this.tipo_comprobante,
                'num_comprobante' : this.num_comprobante,
                'impuesto' : this.impuesto,
                'total' : this.total,
                'moneda' : this.moneda,
                'tipo_cambio' : this.tipo_cambio,
                'observacion' : this.observacion,
                'data': this.arrayDetalle
            }).then(function(response) {
            me.ocultarDetalle();
            me.listarVenta(1,'','num_comprobante');
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        desactivarIngreso(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de anular este ingreso?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/ingreso/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso(1,'','num_comprobante');
                        swal(
                        'Anulado!',
                        'El ingreso ha sido anulado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        validarVenta() {
            let me = this;
            var art;

            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];

            me.arrayDetalle.map(function(x){
                if(x.cantidad > x.stock){
                    art ="La cantidad del articulp " + x.codigo + " supera las cantidades disponibles.";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un cliente");
            if (me.tipo_comprobante==0) me.errorMostrarMsjVenta.push("Seleccione un comprobante.");
            if (!me.num_comprobante) me.errorMostrarMsjVenta.push("Ingrese el numero de comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de la venta");
            if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Introdusca articulos para la venta");
            if (me.moneda != 'Peso Mexicano') me.errorMostrarMsjVenta.push("Seleccione el tipo de cambio de la moneda");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
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
            this.errorMostrarMsjArticulo = [];
            this.num_comprobante = 0;
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

                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                me.user=arrayVentaT[0]['usuario'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
                me.moneda = arrayVentaT[0]['moneda'];
                me.tipo_cambio = arrayVentaT[0]['tipo_cambio'];
                me.observacion = arrayVentaT[0]['observacion'];

                moment.locale('es');
                me.fecha_llegada=moment(fechaform).format('llll');
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
            var url= '/articulo/listarArticuloVenta?buscar=' + buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayArticulo = respuesta.articulos.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        agregarDetalleModal(data =[]){
            let me=this;
            if(me.encuentra(data['id'])){
            Swal.fire({
                type: 'error',
                title: 'Lo siento...',
                text: 'Este No° de placa ya esta en el listado!!',
            })
            }
            else{
                me.arrayDetalle.push({
                    idarticulo       : data['id'],
                    articulo         : data['sku'],
                    sku              : data['sku'],
                    codigo           : data['codigo'],
                    idcategoria      : data['idcategoria'],
                    largo            : data['largo'],
                    alto             : data['alto'],
                    metros_cuadrados : data['metros_cuadrados'],
                    terminado        : data['terminado'],
                    espesor          : data['espesor'],
                    precio           : data['precio_venta'],
                    stock            : data['stock'],
                    ubicacion        : data['ubicacion'],
                    categoria        : data['nombre_categoria'],
                    cantidad: 1,
                    descuento : 0

                });
            }
        },
        abrirModal2(index){
            let me = this;
            me.ind = index;
            me.modal2 = 1;
            me.tituloModal      = "Editar Artículo ";
            me.sku              = me.arrayDetalle[index]['sku'];
            me.codigo           = me.arrayDetalle[index]['codigo'];
            me.idcategoria      = me.arrayDetalle[index]['idcategoria'];
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
            me.cantidad         = me.arrayDetalle[index]['cantidad'];
            me.file             = me.arrayDetalle[index]['imagen'];
            me.descripcion_r    = "";
            me.selectCategoria();
        },
        cerrarModal2() {
            this.modal2 = 0;
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
            this.descripcion_r = '';
            this.ind = '';
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
            me.descripcion_r    = me.arrayDetalle[index]['descripcion'];
            me.observacion_r    = me.arrayDetalle[index]['observacion'];
            me.condicion    = me.arrayDetalle[index]['condicion'];
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
            this.descripcion_r = '';
            this.ind = '';
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

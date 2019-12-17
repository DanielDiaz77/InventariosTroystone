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
                    <i class="fa fa-align-justify"></i> Traslados
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- Listado principal -->
                <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-inline">
                            <div class="form-group mb-2 col-12">
                                <div class="input-group">
                                    <select class="form-control mb-1" v-model="criterio">
                                        <option value="num_comprobante">No° Comprobante</option>
                                        <option value="fecha_hora">Fecha</option>
                                        <option value="nueva_ubicacion">Ubicaciones</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarTraslado(1,buscar,criterio,estadoTraslado)" class="form-control mb-1" placeholder="Texto a buscar...">
                                </div>
                                <div class="input-group">
                                    <select class="form-control mb-1" v-model="estadoTraslado">
                                        <option value="">Activo</option>
                                        <option value="Anulado">Cancelado</option>
                                    </select>
                                    <button type="submit" @click="listarTraslado(1,buscar,criterio,estadoTraslado)" class="btn btn-primary mb-1"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm table-hover table-responsive-xl">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Realizó</th>
                                        <th>Tipo Comprobante</th>
                                        <th>No° Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Lugar de Traslado</th>
                                        <th>Estado</th>
                                        <th>Entregado</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayTraslado.length">
                                    <tr v-for="traslado in arrayTraslado" :key="traslado.id">
                                        <td>
                                            <div class="form-inline">
                                                <button type="button" class="btn btn-success btn-sm" @click="verTraslado(traslado.id)">
                                                <i class="icon-eye"></i>
                                                </button>&nbsp;
                                                <button type="button" class="btn btn-outline-danger btn-sm" @click="pdfTraslado(traslado.id)">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </button>&nbsp;
                                                <template v-if="traslado.estado=='Registrado'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="anularTraslado(traslado.id)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </td>
                                        <td v-text="traslado.usuario"></td>
                                        <td v-text="traslado.tipo_comprobante"></td>
                                        <td v-text="traslado.num_comprobante"></td>
                                        <td>{{ convertDateTraslado(traslado.fecha_hora) }}</td>
                                        <td v-text="traslado.nueva_ubicacion"></td>
                                        <td>
                                            <template v-if="traslado.estado != 'Anulado'">
                                                <span class="badge badge-success">Registrado</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger">Cancelado</span>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="traslado.entregado">
                                                <span class="badge badge-success">100%</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger">No entregado</span>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <strong>NO hay traslados registrados o con ese criterio...</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio,estadoTraslado)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio,estadoTraslado)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, criterio,estadoTraslado)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!-- Nueva Traslado -->
                <template v-if="listado==0">
                    <div class="card-body">
                        <!-- Formulario 1 -->
                        <div class="form-group row border">
                            <div class="col-xl-2 col-lg-6 col-md-6 col-12 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Tipo Comprobante (*)</strong></label>
                                    <select v-model="tipo_comprobante" class="form-control ml-2">
                                        <option value="">Seleccione</option>
                                        <option value="TRASLADO">TRASLADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-12 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Número de traslado (*)</strong></label>
                                    <div class="d-flex justify-content-center">
                                        <div><input type="number" readonly :value="getFechaCode" class="form-control"/></div>
                                        <div><input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-12 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Lugar del traslado</strong><span style="color:red;" v-show="nueva_ubicacion==''">(*Seleccione)</span></label>
                                    <select class="form-control" v-model="nueva_ubicacion" :disabled="arrayDetalle.length!=0">
                                        <option value="" disabled>Seleccione lugar de traslado</option>
                                        <option value="Del Musico">Del Músico</option>
                                        <option value="Escultores">Escultores</option>
                                        <option value="Sastres">Sastres</option>
                                        <option value="Mecanicos">Mecánicos</option>
                                        <option value="Tractorista">Tractorista</option>
                                        <option value="San Luis">San Luis</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-12 mt-2 text-center">
                                <label for=""><strong>Elegir Artículos</strong></label>&nbsp;
                                <template v-if="nueva_ubicacion == ''"><p class="text-danger font-weight-bold">Selecciona la nueva ubicación</p></template>
                                <template v-else><button @click="abrirModal()" class="btn btn-primary">&bull;&bull;&bull;</button></template>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-12 text-center">
                                <label for=""><strong>Imagen</strong></label>
                                <input type="file" :src="imagen" @change="obtenerImagen" class="form-control-file">
                            </div>
                            <div class="col-md-12 text-center">
                                <div v-show="errorTraslado" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjTraslado" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabla detalle -->
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>No°</th>
                                            <th>Opciones</th>
                                            <th>Material</th>
                                            <th>SKU</th>
                                            <th>No° Placa</th>
                                            <th>Terminado</th>
                                            <th>Espesor</th>
                                            <th>largo</th>
                                            <th>Alto</th>
                                            <th>Metros <sup>2</sup></th>
                                            <th>Ubicacion</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td width="10px">{{ (index + 1) }}</td>
                                            <td>
                                                <div class="form-inline">
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>&nbsp;
                                                    <!-- <button type="button" @click="abrirModal2(index)" class="btn btn-success btn-sm">
                                                        <i class="icon-eye"></i>
                                                    </button> &nbsp; -->
                                                </div>
                                            </td>
                                            <td v-text="detalle.categoria"></td>
                                            <td v-text="detalle.articulo"></td>
                                            <td v-text="detalle.codigo"></td>
                                            <td v-text="detalle.terminado"></td>
                                            <td v-text="detalle.espesor"></td>
                                            <td v-text="detalle.largo"></td>
                                            <td v-text="detalle.alto"></td>
                                            <td v-text="detalle.metros_cuadrados"></td>
                                             <td v-text="detalle.ubicacion"></td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Solo hay: {{detalle.stock}} disponibles</span>
                                                <input v-model="detalle.cantidad" min="1" type="number" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="11" class="text-center">
                                                <strong>NO hay artículos agregados...</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Observaciones -->
                        <div class="form-group row d-flex justify-content-around">
                            <div class="col-md-4 text-center">
                                <label for="exampleFormControlTextarea2"><strong>Observaciones</strong></label>
                                <textarea class="form-control rounded-0" rows="3" maxlength="256" v-model="observacion_traslado"></textarea>
                            </div>&nbsp;
                            <div class="col-md-4 text-center d-flex justify-content-center">
                                <template v-if="imagenMinatura !='images/traslados/null'">
                                    <lightbox class="m-0" album="" :src="imagen">
                                        <figure>
                                            <img width="300" height="200" class="img-responsive img-fluid imgcenter" :src="imagen" alt="Foto del artículo">
                                        </figure>
                                    </lightbox>&nbsp;
                                    <div class="col-1" v-if="showElim">
                                        <button type="button" class="btn btn-danger btn-circle float-left" aria-label="Eliminar imagen" @click="eliminarImagen(traslado_id,imagen)">
                                            <i class="fa fa-times"></i>
                                        </button>&nbsp;
                                    </div>
                                </template>
                            </div>&nbsp;
                        </div>
                        <!-- Buttons Cerrar & Registrar -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarTraslado()">Registrar Traslado</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Ver Traslado -->
                <template v-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row d-flex justify-content-around">
                            <div class="form-group p-2">
                                <label for=""><strong>Realizó</strong></label>
                                <p v-text="user"></p>
                            </div>
                            <!-- <div class="form-group p-2">
                                <label for=""><strong>Tipo Comprobante</strong></label>
                                <p v-text="tipo_comprobante"></p>
                            </div> -->
                            <div class="form-group p-2">
                                <label for=""><strong>Comprobante</strong></label>
                                <p> {{ tipo_comprobante }} - {{ num_comprobante }} </p>
                            </div>
                            <div class="form-group p-2">
                                <label for=""><strong>Fecha:</strong></label>
                                <p> {{ formatedDate(fecha_hora) }} </p>
                            </div>
                            <div class="form-group p-2">
                                <label for=""><strong>Entregado 100%:</strong> </label>
                                <div v-if="estadoVn !='Anulado'">
                                    <toggle-button @change="cambiarEstadoEntrega(traslado_id)" v-model="btnEntrega" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Anulado</span>
                                </div>
                            </div>
                            <div class="form-group p-2">
                                <label for=""><strong>Trasladados a</strong></label>
                                <p v-text="nueva_ubicacion"></p>
                            </div>
                            <div class="form-group p-2">
                                <label for=""><strong>Estado</strong></label>
                                <!-- <p v-text="estadoVn"></p> -->
                                <template v-if="estadoVn != 'Anulado'">
                                    <span class="badge badge-success">Registrado</span>
                                </template>
                                <template v-else>
                                    <span class="badge badge-danger">Anulado</span>
                                </template>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>No°</th>
                                            <th>Material</th>
                                            <th>SKU</th>
                                            <th>No° Placa</th>
                                            <th>Terminado</th>
                                            <th>Espesor</th>
                                            <th>largo</th>
                                            <th>Alto</th>
                                            <th>Metros <sup>2</sup></th>
                                            <th>Ubicacion Actual</th>
                                            <th>Ubicacion Antigua</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td width="10px">{{ (index + 1) }}</td>
                                            <td v-text="detalle.categoria"></td>
                                            <td v-text="detalle.sku"></td>
                                            <td v-text="detalle.codigo"></td>
                                            <td v-text="detalle.terminado"></td>
                                            <td v-text="detalle.espesor"></td>
                                            <td v-text="detalle.largo"></td>
                                            <td v-text="detalle.alto"></td>
                                            <td v-text="detalle.metros_cuadrados"></td>
                                            <td v-text="detalle.ubicacion"></td>
                                            <td v-text="detalle.ubicacionAntes"></td>
                                            <td v-text="detalle.cantidad"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                <strong>NO hay artículos agregados...</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row d-flex justify-content-around">
                            <div>
                                <div class="form-group p-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <label for="exampleFormControlTextarea2"><strong>Observaciones</strong></label>
                                        </div>&nbsp;
                                        <div class="mb-2" v-if="estadoVn !='Anulado'">
                                            <template v-if="obsEditable == 0">
                                                <button type="button" class="btn btn-warning btn-sm float-right" @click="editObservacion()">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button" class="btn btn-primary btn-sm float-right" @click="actualizarObservacion(traslado_id)">
                                                    <i class="fa fa-floppy-o"></i>
                                                </button>
                                            </template>&nbsp;
                                        </div>
                                    </div>
                                    <textarea class="form-control rounded-0" rows="5" cols="60" maxlength="256" :readonly="!obsEditable"
                                        v-model="observacion_traslado" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div>
                                <div class="text-center d-flex justify-content-center">
                                    <template v-if="imagenMinatura !='/images/traslados/null'">
                                        <div>
                                            <lightbox class="m-0" album="" :src="imagen">
                                                <figure class="mr-2">
                                                    <img width="300" height="200" class="img-responsive img-fluid imgcenter" :src="imagen" alt="Foto del artículo">
                                                </figure>
                                            </lightbox>&nbsp;
                                        </div>
                                        <div v-if="showElim">
                                            <button type="button" class="btn btn-danger btn-circle float-left" aria-label="Eliminar imagen" @click="eliminarImagen(traslado_id,imagen)">
                                                <i class="fa fa-trash"></i>
                                            </button>&nbsp;
                                        </div>
                                    </template>
                                </div>&nbsp;
                                <div class="d-flex justify-content-center" v-if="estadoVn !='Anulado'">
                                    <div>
                                        <label for=""><strong>Actualizar Imagen</strong></label>
                                        <input type="file" :src="imagen" @change="obtenerImagen" class="form-control-file">
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-primary btn-circle" @click="updImage()">
                                            <i class="fa fa-floppy-o"></i>
                                        </button>&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
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
                        <!-- Filtros Modal Articulos -->
                        <div class="form-group row">
                            <div class="col-md">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioArt">
                                        <option value="sku">Código de material</option>
                                        <option value="codigo">No° de placa</option>
                                        <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscarArt" @keyup.enter="listarArticulo(1,buscarArt,criterioArt,bodegaArt,acabadoArt)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-model="acabadoArt" @keyup.enter="listarArticulo(1,buscarArt,criterioArt,bodegaArt,acabadoArt)" class="form-control" placeholder="Terminado">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="input-group">
                                    <template v-if="areaUs == 'GDL'">
                                        <select class="form-control" v-model="bodegaArt">
                                            <option value="" disabled>Ubicacion</option>
                                            <option value="">Todas</option>
                                            <option value="Del Musico">Del Músico</option>
                                            <option value="Escultores">Escultores</option>
                                            <option value="Sastres">Sastres</option>
                                            <option value="Mecanicos">Mecánicos</option>
                                            <option value="Tractorista">Tractorista</option>
                                            <option value="San Luis">San Luis</option>
                                        </select>
                                    </template>
                                    <button type="submit" @click="listarArticulo(1,buscarArt,criterioArt,bodegaArt,acabadoArt)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>&nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm text-center table-hover">
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
                                    <th>Terminado</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody v-if="arrayArticulo.length">
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
                                    <td v-text="articulo.terminado"></td>
                                    <td>
                                    <div v-if="articulo.condicion == 1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else-if="articulo.condicion == 3">
                                        <span class="badge badge-warning">Cortado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                    </td>
                                </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <strong>NO hay artículos con ese criterio...</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginacion MODAL -->
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="paginationart.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaArt(paginationart.current_page - 1,buscarArt,criterioArt,bodegaArt,acabadoArt)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumberArt" :key="page" :class="[page == isActivedArt ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaArt(page,buscarArt,criterioArt,bodegaArt,acabadoArt)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="paginationart.current_page < paginationart.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaArt(paginationart.current_page + 1,buscarArt,criterioArt,bodegaArt,acabadoArt)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <hr class="d-block d-sm-block d-md-none">
                        <div class="float-right d-block d-sm-block d-md-none">
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
import VueBarcode from 'vue-barcode';
import VueLightbox from 'vue-lightbox';
import moment from 'moment';
import ToggleButton from 'vue-js-toggle-button'
Vue.component("Lightbox",VueLightbox);
Vue.use(ToggleButton);

    export default {
        data(){
            return {
                //traslado
                traslado_id : 0,
                user: '',
                tipo_comprobante : "TRASLADO",
                num_comprobante : "",
                nueva_ubicacion : "",
                entregado : 0,
                cantidad : 0,
                observacion_traslado : "",
                file_traslado : '',
                arrayTraslado : [],
                arrayDetalle : [],
                estadoVn : "",
                fecha_hora : "",
                obsEditable : 0,

                //traslado back
                CodeDate : "",
                errorTraslado: 0,
                errorMostrarMsjTraslado: [],

                //traslado front
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
                estadoTraslado : "",
                btnEntrega : false,
                sigNum : 0,
                listado : 1,

                //Articulos Info
                tituloModal: "",
                modal: 0,
                id_articulo : 0,
                idcategoria :0,
                codigo: "",
                sku : '',
                terminado : '',
                largo : 0,
                alto : 0,
                metros_cuadrados : 0,
                ubicacion : '',
                file_art : '',
                imagenMinatura : '',
                arrayCategoria : [],
                condicion : 0,
                stock : 0,
                descripcion : "",
                observacion_art : "",
                arrayArticulo : [],
                paginationart : {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
                buscarArt : '',
                criterioArt : 'sku',
                bodegaArt : "",
                acabadoArt : "",
                areaUs : "",
                showElim : false

            };
        },
        components: {
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
            isActivedArt: function(){
                return this.paginationart.current_page;
            },
            pagesNumberArt: function() {
                if(!this.paginationart.to) {
                    return [];
                }

                var from = this.paginationart.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.paginationart.last_page){
                    to = this.paginationart.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
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

            listarTraslado(page,buscar,criterio,estadoTraslado){
                let me=this;
                var url= '/traslado?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&estado='+ estadoTraslado;
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    me.arrayTraslado = respuesta.traslados.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio,estadoTraslado){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarTraslado(page,buscar,criterio,estadoTraslado);
            },
            convertDateTraslado(date){
                moment.locale('es');
                let me=this;
                var datec = moment(date).format('DD MMM YYYY hh:mm:ss a');
                /* console.log(datec); */
                return datec;
            },
            getLastNum(){
                let me=this;
                var url= '/traslado/nextNum';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.sigNum = respuesta.SigNum;
                    /* console.log("Next Num = " + me.sigNum); */
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
            mostrarDetalle(){
                this.listado = 0;
                this.getLastNum();
                this.idarticulo = 0;
                this.idcategoria = 0;
                this.codigo = "";
                this.sku = "";
                this.idcategoria = 0;
                this.terminado = "";
                this.largo = 0;
                this.alto = 0;
                this.metros_cuadrados = 0;
                this.ubicacion = "";
                this.stock = 0;
                this.descripcion = "";
                this.arrayDetalle = [];
                this.imagenMinatura = 'images/traslados/null';
                /* console.log("Next Num Detalle = " + parseInt(this.sigNum)); */
                this.num_comprobante = (parseInt(this.sigNum)+1);
                this.selectCategoria();

            },
            ocultarDetalle(){
                this.listado = 1;
                this.getLastNum();
                this.idarticulo = 0;
                this.idcategoria = 0;
                this.codigo = "";
                this.sku = "";
                this.idcategoria = 0;
                this.terminado = "";
                this.largo = 0;
                this.alto = 0;
                this.metros_cuadrados = 0;
                this.ubicacion = "";
                this.stock = 0;
                this.descripcion = "";
                this.arrayDetalle = [];
                this.num_comprobante = "";
                this.traslado_id = 0;
                this.user= '';
                this.tipo_comprobante = "TRASLADO";
                this.nueva_ubicacion = "";
                this.entregado = 0;
                this.cantidad = 0;
                this.observacion_traslado = "";
                this.file_traslado = '';
                this.arrayDetalle = [];
                this.obsEditable = 0;
                this.imagenMinatura = "";
                this.listarTraslado(1,'','num_comprobante','');
            },
            obtenerImagen(e){
                let img = e.target.files[0];
                /*  console.log(img); */
                this.file_traslado = img;
                this.cargarImagen(img);
            },
            cargarImagen(img){
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.imagenMinatura = e.target.result;
                    this.file_traslado =  e.target.result;
                }
                reader.readAsDataURL(img);
            },
            cerrarModal() {
                this.modal = 0;
                this.buscarArt = "";
                this.bodegaArt = "";
                this.acabadoArt = "";
                this.errorTraslado = 0;
                this.errorMostrarMsjTraslado = [];
            },
            abrirModal() {
                this.arrayArticulo=[];
                this.modal = 1;
                this.tituloModal = "Seleccionar Artículos";
                this.listarArticulo(1,'','sku','','');
            },
            listarArticulo(page,buscar,criterio,bodega,acabado){
                let me=this;
                var url= '/articulo/listarArticuloVenta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&bodega=' + bodega + '&acabado=' + acabado;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                    me.paginationart= respuesta.pagination;
                    me.areaUs = respuesta.userarea;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPaginaArt(page,buscar,criterio,bodega,acabado){
                let me = this;
                //Actualiza la página actual
                me.paginationart.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarArticulo(page,buscar,criterio,bodega,acabado);
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
            encuentraUbicacion(ubicacion){
                var sw=0;
                var NewLocation = this.nueva_ubicacion;
                if(ubicacion == NewLocation){
                    sw=true;
                }
                return sw;
            },
            agregarDetalleModal(data =[]){
                let me=this;

                if(me.encuentraUbicacion(data['ubicacion'])){
                    Swal.fire({
                        type: 'error',
                        title: 'Lo siento...',
                        text: 'Esta de placa esta en la ubicacion a la que quieres trasladar!!',
                    })
                    return;
                }

                if(me.encuentra(data['id'])){
                    Swal.fire({
                        type: 'error',
                        title: 'Lo siento...',
                        text: 'Esta de placa ya esta en el listado!!',
                    })
                }
                else{
                    me.arrayDetalle.push({
                        idarticulo       : data['id'],
                        articulo         : data['sku'],
                        codigo           : data['codigo'],
                        idcategoria      : data['idcategoria'],
                        categoria        : data['nombre_categoria'],
                        largo            : data['largo'],
                        alto             : data['alto'],
                        metros_cuadrados : data['metros_cuadrados'],
                        terminado        : data['terminado'],
                        espesor          : data['espesor'],
                        precio           : data['precio_venta'],
                        stock            : data['stock'],
                        ubicacion        : data['ubicacion'],
                        categoria        : data['nombre_categoria'],
                        origen           : data['origen'],
                        contenedor       : data['contenedor'],
                        file             : data['file'],
                        descripcion      : data['descripcion'],
                        observacion      : data['observacion'],
                        fecha_llegada    : data['fecha_llegada'],
                        cantidad: 1,
                        descuento : 0

                    });
                }
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            validarTraslado() {
                let me = this;
                var art;

                me.errorTraslado = 0;
                me.errorMostrarMsjTraslado = [];

                me.arrayDetalle.map(function(x){
                    if(x.cantidad > x.stock){
                        art ="La cantidad del articulo " + x.codigo + " supera las cantidades disponibles.";
                        me.errorMostrarMsjTraslado.push(art);
                    }
                });

                if (!me.num_comprobante) me.errorMostrarMsjTraslado.push("Ingrese el numero de comprobante");
                if (!me.nueva_ubicacion) me.errorMostrarMsjTraslado.push("Seleccione la ubicación del traslado");
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjTraslado.push("Introdusca articulos para el traslado");

                if (me.errorMostrarMsjTraslado.length) me.errorTraslado = 1;

                return me.errorTraslado;
            },
            registrarTraslado(){

                if (this.validarTraslado()) {
                    return;
                }

                let me = this;

                var numcomp = "T-".concat(me.CodeDate,"-",me.num_comprobante);

                axios.post('/traslado/registrar',{
                    'tipo_comprobante': this.tipo_comprobante,
                    'num_comprobante' : numcomp,
                    'nueva_ubicacion' : this.nueva_ubicacion,
                    'observacion'     : this.observacion_traslado,
                    'file'            : this.file_traslado,
                    'data': this.arrayDetalle
                }).then(function(response) {
                    me.ocultarDetalle();
                    me.listarTraslado(1,'','num_comprobante','');
                    me.tipo_comprobante = "TRASLADO";
                    me.num_comprobante = 0;
                    me.idarticulo = 0;
                    me.articulo = "";
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.observacion = "";
                    me.file_traslado = "";
                    me.nueva_ubicacion = "";
                    me.arrayDetalle = [];

                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            verTraslado(id){
                let me = this;
                me.listado = 2;

                //Obtener los datos del ingreso
                var arrayTrasladoT=[];
                var url= '/traslado/obtenerCabecera?id=' + id;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayTrasladoT = respuesta.traslado;

                    /* console.log("Obs Traslado" + arrayTrasladoT[0]['id'] +  " : " + arrayTrasladoT[0]['obstraslado']); */

                    var fechaform  = arrayTrasladoT[0]['fecha_hora'];

                    var total_parcial = 0;

                    me.traslado_id = arrayTrasladoT[0]['id'];
                    me.tipo_comprobante=arrayTrasladoT[0]['tipo_comprobante'];
                    me.num_comprobante=arrayTrasladoT[0]['num_comprobante'];
                    me.user=arrayTrasladoT[0]['usuario'];
                    me.nueva_ubicacion = arrayTrasladoT[0]['nueva_ubicacion'];
                    me.observacion_traslado = arrayTrasladoT[0]['obstraslado'];
                    me.estadoVn = arrayTrasladoT[0]['estado'];
                    me.fecha_hora = arrayTrasladoT[0]['fecha_hora'];
                    me.entregado = arrayTrasladoT[0]['entregado'];
                    /* moment.locale('es');
                    me.fecha_registro=moment(fechaform).format('dddd DD MMM YYYY hh:mm:ss a'); */

                    if(me.entregado ==1){
                        me.btnEntrega = true;
                    }

                    let hasImg = '/images/traslados/' + arrayTrasladoT[0]['file'];

                    if(hasImg != '/images/traslados/null'){
                        me.imagenMinatura = '/images/traslados/'+ arrayTrasladoT[0]['file'];
                        me.showElim = true;
                        /* console.log('Elim: '+me.showElim); */
                    }else{
                        me.imagenMinatura = '/images/traslados/null';
                        me.showElim = false;
                        /* console.log('Elim: '+me.showElim); */
                    }

                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los detalles del ingreso
                var url= '/traslado/obtenerDetalles?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatedDate(date){
                moment.locale('es');
                let me=this;
                var datec = moment(date).format('DD MMM YYYY hh:mm:ss a');
                /* console.log(datec); */
                return datec;
            },
            editObservacion(){
                let me = this;
                me.obsEditable = 1;
            },
            actualizarObservacion(id){
                let me = this;
                axios.post('/traslado/actualizarObservacion',{
                    'id': id,
                    'observacion' : this.observacion_traslado
                }).then(function (response) {
                    me.obsEditable = 0;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updImage(){
                let me = this;
                axios.put('/traslado/updImagen',{
                    'file': this.file_traslado,
                    'id' : this.traslado_id
                }).then(function(response) {

                    /* me.ocultarDetalle(); */
                    /* me.listarTraslado(1,'','num_comprobante'); */
                    Swal.fire({
                        type: 'success',
                        title: 'Completado...',
                        text: 'La imagen ha sido actualizada!',
                    })
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
                    title: "¿Esta de eliminar la imagen de este traslado?",
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
                        axios.put('/traslado/eliminarImg', {
                            'id' : id
                        }).then(function(response) {
                            /* me.listarVenta(1,'','num_comprobante'); */
                            me.imagenMinatura = '/images/traslados/null';
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
            },
            cambiarEstadoEntrega(id){
                let me = this;
                if(me.btnEntrega == true){
                    me.entregado = 1;
                }else{
                    me.entregado = 0;
                }
                axios.post('/traslado/cambiarEntrega',{
                    'id': id,
                    'entregado' : this.entregado
                }).then(function (response) {
                    if(me.entregado == 1){
                        Swal.fire(
                            "Completado!",
                            "El traslado ha sido marcado como entregado con éxito.",
                            "success"
                        )
                    }else{
                        Swal.fire(
                            "Atención!",
                            "El traslado ha sido marcado como no entregado.",
                            "warning"
                        )
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            anularTraslado(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: "¿Esta seguro de anular este traslado? Este proceso es irreversible!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Aceptar!",
                    cancelButtonText: "Cancelar!",
                    reverseButtons: true
                })
                .then(result => {
                    if (result.value) {
                        let me = this;
                        axios.put('/traslado/desactivar',{
                            'id': id
                        }).then(function (response) {
                            Swal.fire({
                                type: 'success',
                                title: 'Completado...',
                                text: 'El traslado ha sido anulado con exito!',
                            })
                            me.listarTraslado(1,'','num_comprobante','');

                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else if (result.dismiss === swal.DismissReason.cancel){
                    }
                })
            },
            pdfTraslado(id){
                window.open('/traslado/pdf/'+id);
            },

        },
        mounted() {
            /* console.log('Component Traslados mounted.'); */
            this.listarTraslado(1,this.buscar, this.criterio,this.estadoTraslado);
            this.getLastNum();
        }
    }
</script>

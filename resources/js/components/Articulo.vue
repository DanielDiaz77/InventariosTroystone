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
                <i class="fa fa-align-justify"></i> Artículos
               <!--  <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary" v-if="showNew">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button> -->
            </div>
            <!-- Listado -->
            <template v-if="listado==1">
                <div class="card-body">
                    <div class="form-inline">
                        <div class="form-group mb-2 col-sm-10">
                            <div class="input-group">
                                <select class="form-control mb-1" v-model="criterio">
                                    <option value="descripcion">Descripción</option>
                                    <option value="sku">Código de material</option>
                                    <option value="codigo">No° de placa</option>
                                    <option value="idcategoria">Material</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)" class="form-control mb-1" placeholder="Texto a buscar">
                                <input type="text" v-model="acabado" @keyup.enter="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)" class="form-control mb-1" placeholder="Terminado">
                            </div>
                            <div class="input-group">

                                <select class="form-control mb-1" v-model="bodega" @change="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)">
                                    <template v-if="zona=='GDL'">
                                        <option value="">Todas</option>
                                        <option value="Del Musico">Del Músico</option>
                                        <option value="Escultores">Escultores</option>
                                        <option value="Sastres">Sastres</option>
                                        <option value="Mecanicos">Mecánicos</option>
                                        <option value="Tractorista">Tractorista</option>
                                    </template>
                                    <template v-else>
                                        <option value="San Luis">San Luis</option>
                                    </template>
                                </select>
                                <button type="submit" @click="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)" class="btn btn-sm btn-primary mb-1"><i class="fa fa-search"></i>Buscar</button>
                            </div>
                            <div class="input-group input-group-sm ml-xl-5">
                                <select class="form-control" id="tipofact" name="tipofact" v-model="estadoArt" @change="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)">
                                    <option value="1">Disponible</option>
                                    <option value="2">Vendido</option>
                                    <option value="3">Cortado</option>
                                </select>
                                <button class="btn btn-sm btn-info" type="button"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Filtros</button>
                            </div>
                            <div class="input-group input-group-sm ml-xl-5 mt-1 mt-md-0">
                                <button class="btn btn-sm btn-danger" type="button"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp; Area</button>
                                <select class="form-control" v-model="zona" @change="listarArticulo(1,buscar,criterio,bodega,acabado,estadoArt)">
                                    <option value="GDL">Guadalajara</option>
                                    <option value="SLP">San Luis</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mt-1 mt-sm-0 ml-md-2 ml-lg-5">
                                <button @click="abrirModal3()" class="btn btn-success btn-sm">Reporte <i class="fa fa-file-excel-o"></i></button>
                            </div>
                        </div>

                        <div class="form-group mb-2 col-sm-2 float-right">
                            <label class="mb-1 ml-sm-5" for=""><strong>Total:</strong></label>
                            <p class="mb-1" v-text="totres"></p>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm text-center table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>Opciones</th>
                                <th>No. Placa</th>
                                <th>Código de Material</th>
                                <th>Material</th>
                                <th>Descripción</th>
                                <th>Largo</th>
                                <th>Alto</th>
                                <th>Metros<sup>2</sup></th>
                                <th>Espesor</th>
                                <th>Terminado</th>
                                <th>Bodega de descarga</th>
                                <th>Estado</th>
                                <th>Stock</th>
                                <th v-if="estadoArt!=2">Comprometido</th>
                                <th v-else>Venta</th>
                            </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td>
                                        <div class="form-inline">
                                            <template v-if="articulo.condicion == 1 && articulo.stock > 0">
                                                <button type="button" @click="editArticulo(articulo)" class="btn btn-warning btn-sm">
                                                    <i class="icon-pencil"></i>
                                                </button> &nbsp;
                                            </template>
                                            <template v-else></template>
                                            <template v-if="articulo.condicion == 1">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <template v-else-if="articulo.condicion == 0">
                                                <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                            <template v-else></template>&nbsp;
                                            <button type="button" @click="abrirModal2('articulo','visualizar',articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                        </div>
                                    </td>
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.sku"></td>
                                    <td v-text="articulo.nombre_categoria"></td>
                                    <td v-text="articulo.descripcion"></td>
                                    <td v-text="articulo.largo"></td>
                                    <td v-text="articulo.alto"></td>
                                    <td v-text="articulo.metros_cuadrados"></td>
                                    <td v-text="articulo.espesor"></td>
                                    <td v-text="articulo.terminado"></td>
                                    <td v-text="articulo.ubicacion"></td>
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
                                    <td v-text="articulo.stock"></td>
                                    <td v-if="estadoArt!=2">
                                        <div v-if="articulo.comprometido">
                                            <span class="badge badge-success">Si</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">No</span>
                                        </div>
                                    </td>
                                    <td v-else>
                                        {{ articulo.venta }}
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <strong>NO hay artículos con ese criterio...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio,bodega,acabado,estadoArt)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio,bodega,acabado,estadoArt)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, criterio,bodega,acabado,estadoArt)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </template>
            <!-- Fin Listado -->

            <!-- Edit Articulo -->
            <template v-if="listado==0">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-12 text-center">
                           <h3 v-text="tituloModal"></h3>
                        </div>&nbsp;
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <div v-if="isEdition && estado">
                            <label for=""><strong>Comprometido: </strong></label>
                            <toggle-button @change="cambiarComprometido(articulo_id)" v-model="btnComprometido" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                        </div>&nbsp;
                        <div v-if="usuario" class="mt-0 mt-md-1">
                            <label for=""><strong>Actualizo: </strong> {{ usuario }} </label>
                            <!-- <p v-text="usuario"></p> -->
                        </div>&nbsp;
                    </div>
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">

                        <div class="row">
                            <div class="input-group input-group-sm col-12 col-lg-3 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Material</span>
                                </div>
                                <select class="form-control" v-model="idcategoria">
                                    <option value="0" disabled>Seleccione un material</option>
                                    <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Código de material</span>
                                </div>
                                <input type="text" v-model="sku" class="form-control" placeholder="Código de material"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">No° de placa</span>
                                </div>
                                <input type="text" v-model="codigo" class="form-control" placeholder="Num de placa"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Terminado</span>
                                </div>
                                <select class="form-control" v-model="terminado">
                                    <option value='' disabled>Seleccione un de terminado</option>
                                    <option value="Pulido">Pulido</option>
                                    <option value="Al Corte">Al Corte</option>
                                    <option value="Leather">Leather</option>
                                    <option value="Mate">Mate</option>
                                    <option value="Seda">Seda</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                             <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Largo</span>
                                </div>
                                <input type="number" v-model="largo" min="1" class="form-control" placeholder=""/>
                            </div>
                             <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Alto</span>
                                </div>
                                <input type="number" min="1" v-model="alto" class="form-control" placeholder=""/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Metros<sup>2</sup></span>
                                </div>
                                <input type="number" readonly :value="calcularMts" class="form-control"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Espesor</span>
                                </div>
                                 <input type="number" min="1" v-model="espesor" class="form-control" placeholder=""/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Bodega de descarga</span>
                                </div>
                                <select class="form-control" v-model="ubicacion">
                                    <option value="" disabled>Seleccione una bodega de descarga</option>
                                    <option value="Del Musico">Del Músico</option>
                                    <option value="Escultores">Escultores</option>
                                    <option value="Sastres">Sastres</option>
                                    <option value="Mecanicos">Mecánicos</option>
                                    <option value="Tractorista">Tractorista</option>
                                    <option value="San Luis">San Luis</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Contenedor</span>
                                </div>
                                <input type="text" v-model="contenedor" class="form-control" placeholder="Contenedor"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Fecha de llegada</span>
                                </div>
                                <input type="date" v-model="fecha_llegada" class="form-control" placeholder="Fecha de llegada"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Origen</span>
                                </div>
                                <input type="text" v-model="origen" class="form-control" placeholder="Origen"/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3  mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Precio m<sup>2</sup></span>
                                </div>
                                <input type="number" min="1" value="0" step="any" v-model="precio_venta" class="form-control" placeholder=""/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Stock</span>
                                </div>
                                <input type="number" min="1" v-model="stock" class="form-control" placeholder="" disabled/>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-3">
                                <div class="custom-file">
                                    <label class="custom-file-label">Imagen </label>
                                    <input type="file" class="custom-file-input form-control-sm" :src="imagen" @change="obtenerImagen">
                                </div>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-4 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Descripción</span>
                                </div>
                                <textarea class="form-control rounded-0" style="resize: none;" rows="3" maxlength="256" v-model="descripcion"></textarea>
                            </div>
                            <div class="input-group input-group-sm col-12 col-lg-4 mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text">Observaciones</span>
                                </div>
                                <textarea class="form-control rounded-0" style="resize: none;" rows="3" maxlength="256" v-model="observacion"></textarea>
                            </div>

                        </div>
                        <div class="form-group row d-flex justify-content-around">
                            <div class="d-flex justify-content-center">
                                <template v-if="imagenMinatura !='images/null'">
                                    <div>
                                        <lightbox class="m-0" album="" :src="imagen">
                                            <figure>
                                                <img width="300" height="200" class="img-responsive img-fluid imgcenter" :src="imagen" alt="Foto del artículo">
                                            </figure>
                                        </lightbox>&nbsp;
                                    </div>
                                    <div v-if="showElim != false">
                                        <button type="button" class="btn btn-danger rounded-circle ml-1" aria-label="Eliminar imagen" @click="eliminarImagen(articulo_id,imagen)">
                                            <i class="fa fa-times"></i>
                                        </button>&nbsp;
                                    </div>
                                    <div v-if="remoFile">
                                        <button type="button" class="btn btn-danger rounded-circle ml-1" aria-label="Eliminar imagen" @click="limpiarFile()">
                                            <i class="fa fa-times"></i>
                                        </button>&nbsp;
                                    </div>
                                </template>
                            </div>
                            <div class="d-none d-sm-none d-md-block">
                                <barcode :value="codigo" :options="{format: 'EAN-13'}">
                                    Generando código de barras.
                                </barcode>
                            </div>
                        </div>
                        <div v-show="errorArticulo" class="form-group row div-error">
                            <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
                            </div>
                        </div>
                    </form>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="closeEdit()"  class="btn btn-secondary">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Fin Edit Articulo -->
        </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" data-spy="scroll"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row" v-if="isEdition && estado">
                            <label class="col-md-3 form-control-label" for="text-input">Comprometido: </label>
                            <div class="col-md-3">
                                <toggle-button @change="cambiarComprometido(articulo_id)" v-model="btnComprometido" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                            </div>
                            <label class="col-md-3 form-control-label" for="text-input">Actualizo: </label>
                            <p  class="col-md-3" v-text="usuario"></p>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Material</label>
                            <div class="col-md-9">
                            <select class="form-control" v-model="idcategoria">
                                <option value="0" disabled>Seleccione un material</option>
                                <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Código</label>
                            <div class="col-md-9">
                                <input type="text" v-model="codigo" class="form-control" placeholder="Código de barras"/>
                                <barcode :value="codigo" :options="{formar: 'EAN-13'}">
                                    Generando código de barras.
                                </barcode>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Código de material</label>
                            <div class="col-md-9">
                                <input type="text" v-model="sku" class="form-control" placeholder="Código de material"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Terminado</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="terminado">
                                    <option value='' disabled>Seleccione un de terminado</option>
                                    <option value="Pulido">Pulido</option>
                                    <option value="Al Corte">Al Corte</option>
                                    <option value="Leather">Leather</option>
                                    <option value="Mate">Mate</option>
                                    <option value="Seda">Seda</option>
                                    <option value="Otro">Otro</option>
                                </select>
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
                                <input type="number" readonly :value="calcularMts" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Espesor</label>
                            <div class="col-md-9">
                                <input type="number" min="1" v-model="espesor" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Precio m<sup>2</sup></label>
                            <div class="col-md-9">
                                <input type="number" min="1" value="0" step="any" v-model="precio_venta" class="form-control" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Bodega de descarga</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="ubicacion">
                                    <option value="" disabled>Seleccione una bodega de descarga</option>
                                    <option value="Del Musico">Del Músico</option>
                                    <option value="Escultores">Escultores</option>
                                    <option value="Sastres">Sastres</option>
                                    <option value="Mecanicos">Mecánicos</option>
                                    <option value="Tractorista">Tractorista</option>
                                    <option value="San Luis">San Luis</option>
                                </select>
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
                                <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">observacion</label>
                            <div class="col-md-9">
                                <input type="email" v-model="observacion" class="form-control" placeholder="Ingrese las observaciones"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Origen</label>
                            <div class="col-md-9">
                                <input type="text" v-model="origen" class="form-control" placeholder="Origen"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Contenedor</label>
                            <div class="col-md-9">
                                <input type="text" v-model="contenedor" class="form-control" placeholder="Contenedor"/>
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
                        <div class="form-group row">
                            <template v-if="imagenMinatura !='images/null'">
                                <div class="col-3"></div>
                                <div class="col-5">
                                    <lightbox class="m-0" album="" :src="imagen">
                                        <figure>
                                            <img width="300" height="200" class="img-responsive img-fluid imgcenter" :src="imagen" alt="Foto del artículo">
                                        </figure>
                                    </lightbox>&nbsp;
                                </div>
                                <div class="col-1" v-if="showElim">
                                    <button type="button" class="btn btn-danger btn-circle float-left" aria-label="Eliminar imagen" @click="eliminarImagen(articulo_id,imagen)">
                                        <i class="fa fa-times"></i>
                                    </button>&nbsp;
                                </div>
                                <div class="col-3"></div>
                            </template>
                        </div>
                        <div v-show="errorArticulo" class="form-group row div-error">
                            <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
                            </div>
                        </div>
                    </form>
                    <hr class="d-block d-sm-block d-md-none">
                    <div class="float-right d-block d-sm-block d-md-none">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
                    </div>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

     <!--Inicio del modal Visualizar articulo-->
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
                <template v-if="file">
                    <!-- <lightbox class="m-0" album="" :src="'http://inventariostroystone.com/images/'+file"> -->
                    <lightbox class="m-0" album="" :src="'images/'+file">
                        <!-- <img class="img-responsive img-fluid imgcenter" width="500px" :src="'http://inventariostroystone.com/images/'+file"> -->
                        <img class="img-responsive img-fluid imgcenter" width="500px" :src="'images/'+file">
                    </lightbox>&nbsp;
                </template>
                <table class="table table-bordered table-striped table-sm text-center table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" colspan="2">Detalle del artículo</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        <td v-text="espesor"></td>
                    </tr>
                    <tr >
                        <td><strong>PRECIO</strong></td>
                        <td v-text="precio_venta"></td>
                    </tr>
                    <tr >
                        <td><strong>BODEGA DE DESCARGA</strong></td>
                        <td v-text="ubicacion"></td>
                    </tr>
                    <tr >
                        <td><strong>STOCK</strong></td>
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
                <hr class="d-block d-sm-block d-md-none">
                <div class="float-right d-block d-sm-block d-md-none">
                    <button type="button" class="btn btn-secondary float-right" @click="cerrarModal2()">Cerrar</button>
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
    <!--Fin del modal-->

    <!-- Modal exportar excel -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-success modal-md " role="document">
            <div class="modal-content content-export">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body ">
                    <!-- <h3 class="mb-3">Generar reporte de ventas</h3> -->
                    <div class="row d-flex justify-content-around">
                        <div class="col-12 col-md-6 mb-2">
                            <label for=""><strong>Ubicacion: </strong></label>
                            <select class="form-control" v-model="bodegaReporte">
                                <option value="" disabled>Seleccione una ubicación</option>
                                <option v-for="bodega in arrayUbicaciones" :key="bodega.ubicacion" :value="bodega.ubicacion" v-text="bodega.ubicacion"></option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <div v-if="bodegaReporte">
                            <button type="button" class="btn btn-primary ml-5" @click="listarExcel(bodegaReporte)">Disponible</button>
                        </div>
                        <div v-if="bodegaReporte">
                            <button type="button" class="btn btn-primary ml-5" @click="listarExcelVendido(bodegaReporte)">No Entregado</button>
                        </div>
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
    <!-- Fin exportar excel -->



  </main>
</template>

<script>

import VueBarcode from 'vue-barcode';
import VueLightbox from 'vue-lightbox';
import ToggleButton from 'vue-js-toggle-button';
Vue.component("Lightbox",VueLightbox);
Vue.use(ToggleButton);
export default {
    data() {
        return {
            articulo_id: 0,
            idcategoria :0,
            nombre_categoria : '',
            codigo : '',
            sku : '',
            terminado : '',
            largo : 0,
            alto : 0,
            metros_cuadrados : 0,
            espesor : 2,
            precio_venta : 0,
            ubicacion : '',
            stock : 1,
            comprometido : 0,
            usuario : '',
            descripcion: '',
            observacion : '',
            origen : '',
            contenedor: '',
            fecha_llegada : '',
            file : '',
            estado : 0,
            imagenMinatura : '',
            arrayArticulo: [],
            modal: 0,
            modal2: 0,
            modal3: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorArticulo: 0,
            errorMostrarMsjArticulo: [],
            pagination : {
                'total'        : 0,
                'current_page' : 0,
                'per_page'     : 0,
                'last_page'    : 0,
                'from'         : 0,
                'to'           : 0,
            },
            offset : 3,
            criterio : 'sku',
            bodega : '',
            acabado : '',
            totres : 0,
            buscar : '',
            arrayCategoria : [],
            btnComprometido : '',
            isEdition : false,
            showElim : false,
            estadoArt : 1,
            arrayUbicaciones : [],
            bodegaReporte : "",
            listado : 1,
            showNew : false,
            remoFile : false,
            zona : "GDL"
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
            calcularMts : function(){
                let me=this;
                let resultado = 0;
                resultado = resultado + (me.alto * me.largo);
                me.metros_cuadrados = resultado;
                return resultado;

            },
            imagen(){
                return this.imagenMinatura;
            }
        },
    methods: {
        listarArticulo (page,buscar,criterio,bodega,acabado,estado){
            let me=this;

            if(me.zona == 'SLP'){
                bodega = 'San Luis';
                me.bodega = 'San Luis';
            }else{
                if(bodega == 'San Luis'){
                    bodega = "";
                    me.bodega = "";
                }
            }

            var url= '/articulo?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&bodega='
            + bodega + '&acabado=' + acabado + '&estado=' + estado;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayArticulo = respuesta.articulos.data;
                me.pagination= respuesta.pagination;
                me.totres = respuesta.total;
                /* me.zona = respuesta.userarea; */
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
        cambiarPagina(page,buscar,criterio,bodega,acabado,estado){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarArticulo(page,buscar,criterio,bodega,acabado,estado);
        },
        obtenerImagen(e){
            let img = e.target.files[0];
           /*  console.log(img); */
            this.file = img;
            this.cargarImagen(img);
        },
        cargarImagen(img){
            let reader = new FileReader();
            reader.onload = (e) => {
                this.imagenMinatura = e.target.result;
                this.file =  e.target.result;
                this.remoFile = true;
            }
            reader.readAsDataURL(img);
        },
        limpiarFile(){
            this.imagenMinatura = "images/null";
            this.file =  "";
            this.remoFile = false;
        },
        registrarArticulo(){

                if (this.validarArticulo()){
                    return;
                }
                let me = this;

                axios.post("/articulo/registrar",{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'sku' : this.sku,
                    'terminado' : this.terminado,
                    'largo' : this.largo,
                    'alto' : this.alto,
                    'metros_cuadrados' : this.metros_cuadrados,
                    'espesor' : this.espesor,
                    'precio_venta' : this.precio_venta,
                    'ubicacion' : this.ubicacion,
                    'stock': this.stock,
                    'descripcion': this.descripcion,
                    'observacion' : this.observacion,
                    'origen' : this.origen,
                    'contenedor' : this.contenedor,
                    'fecha_llegada' : this.fecha_llegada,
                    'file' : this.file
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarArticulo(1,'','sku','','',1);
                }).catch(function (error) {
                    console.log(error);
                });
        },
        actualizarArticulo() {

            var page = this.pagination.current_page;
            var crit =  this.criterio;
            var busc = this.buscar;
            var bod = this.bodega;
            var aca = this.acabado;
            let art = this.sku;
            let cdn = this.codigo;
            if (this.validarArticulo()) {
                return;
            }
            let me = this;
            axios.put("/articulo/actualizar", {
                'idcategoria': this.idcategoria,
                'codigo': this.codigo,
                'sku' : this.sku,
                'terminado' : this.terminado,
                'largo' : this.largo,
                'alto' : this.alto,
                'metros_cuadrados' : this.metros_cuadrados,
                'espesor' : this.espesor,
                'precio_venta' : this.precio_venta,
                'ubicacion' : this.ubicacion,
                'stock': this.stock,
                'descripcion': this.descripcion,
                'observacion' : this.observacion,
                'origen' : this.origen,
                'contenedor' : this.contenedor,
                'fecha_llegada' : this.fecha_llegada,
                'file' : this.file,
                'id': this.articulo_id
            })
            .then(function(response) {
                Swal.fire(
                    "Actualizado!",
                    `El articulo ${ art } - ${ cdn } ha sido actualizado con éxito`,
                    "success"
                )
                me.closeEdit();
                me.listarArticulo(page,busc,crit,bod,aca,1);
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        desactivarArticulo(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de desactivar este artículo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/articulo/desactivar', {
                        'id' : id
                    }).then(function(response) {
                        me.listarArticulo(1,'','sku','','',1);
                        swalWithBootstrapButtons.fire(
                            "Desactivado!",
                            "La categoría ha sido desactivada con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        activarArticulo(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de activar esta artículo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/articulo/activar', {
                        'id' : id
                    }).then(function(response) {
                        me.listarArticulo(1,'','sku','','',1);
                        swalWithBootstrapButtons.fire(
                            "Activado!",
                            "Artículo activado con éxito.",
                            "success"
                        )
                    }).catch(function(error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        validarArticulo() {
            this.errorArticulo = 0;
            this.errorMostrarMsjArticulo = [];

            if (this.idcategoria==0) this.errorMostrarMsjArticulo.push("Selecciona una categoría.");
            if (!this.sku) this.errorMostrarMsjArticulo.push("El código del material no puede estar vacío.");
            if (!this.terminado) this.errorMostrarMsjArticulo.push("El terminado del artículo no puede estar vacío.");
            if (!this.largo) this.errorMostrarMsjArticulo.push("El largo del artículo no puede estar vacío.");
            if (!this.alto) this.errorMostrarMsjArticulo.push("El alto del artículo no puede estar vacío.");
            if (!this.metros_cuadrados) this.errorMostrarMsjArticulo.push("Los metros cuadrados del artículo no pueden estar vacíos.");
            if (!this.espesor) this.errorMostrarMsjArticulo.push("El espesor del artículo no puede estar vacío.");
            if (!this.ubicacion) this.errorMostrarMsjArticulo.push("Seleccione una bodega de descarga");
            if (!this.stock) this.errorMostrarMsjArticulo.push("El stock del artículo debe ser un número y no puede estar vacío.");
            if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

            return this.errorArticulo;
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.idcategoria = 0;
            this.codigo = '';
            this.sku = '';
            this.terminado = '';
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.espesor = 0;
            this.precio_venta  = 0;
            this.ubicacion = '';
            this.stock = 1;
            this.descripcion= '';
            this.observacion = '';
            this.origen = '';
            this.contenedor  = '';
            this.fecha_llegada = '';
            this.file = '';
            this.errorArticulo = 0;
            this.imagenMinatura = '';
            this.btnComprometido = '';
            this.comprometido = 0;
            this.usuario = '';
            this.isEdition = false;
            this.file = "";
            this.showElim = false;
            this.listarArticulo(1,'','sku','','',1);

        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "articulo": {
                    switch (accion) {
                        case "registrar": {
                            this.modal = 1;
                            this.tituloModal = "Registrar Artículo";
                            this.idcategoria = 0;
                            this.codigo = '';
                            this.sku = '';
                            this.terminado = '';
                            this.largo = 0;
                            this.alto = 0;
                            this.metros_cuadrados = 0;
                            this.espesor = 2;
                            this.precio_venta = 0;
                            this.ubicacion = '';
                            this.stock = 1;
                            this.descripcion= '';
                            this.observacion = '';
                            this.origen = '';
                            this.contenedor = '';
                            this.fecha_llegada = '';
                            this.file = '';
                            this.tipoAccion = 1;
                            this.imagenMinatura = 'images/null';
                            this.showElim = false;
                            break;
                        }
                        case "actualizar": {
                            this.modal = 1;
                            this.tituloModal = "Actualizar Artículo";
                            this.tipoAccion = 2;
                            this.articulo_id = data['id'];
                            this.idcategoria = data['idcategoria'];
                            this.codigo = data['codigo'];
                            this.sku = data['sku'];
                            this.terminado = data['terminado'];
                            this.largo = data['largo'];
                            this.alto = data['alto'];
                            this.metros_cuadrados = data['metros_cuadrados'];
                            this.espesor = data['espesor'];
                            this.precio_venta = data['precio_venta'];
                            this.ubicacion = data['ubicacion'];
                            this.stock = data['stock'];
                            this.descripcion= data['descripcion'];
                            this.observacion = data['observacion'];
                            this.origen = data['origen'];
                            this.contenedor = data['contenedor'];
                            this.fecha_llegada = data['fecha_llegada'];
                            /* this.imagenMinatura = 'http://inventariostroystone.com/images/' + data['file']; */
                            this.estado = data['condicion'];
                            this.comprometido = data['comprometido'];
                            this.usuario = data['usuario'];
                            this.isEdition = true;

                            if(this.comprometido == 1){
                                this.btnComprometido = true;
                            }else{
                                this.btnComprometido = false;
                            }

                            let hasImg = 'images/' + data['file'];

                            console.log("HasImg: " + hasImg);

                            if(hasImg != 'images/null'){
                                this.showElim = true;
                                this.imagenMinatura = 'images/' + data['file'];
                            }else{
                                this.showElim = false;
                                this.imagenMinatura = 'images/null';
                            }

                            break;
                        }
                    }
                }
            }
            this.selectCategoria();
        },
        cambiarComprometido(id){
          let me = this;
            if(me.btnComprometido == true){
                me.comprometido = 1;
            }else{
                me.comprometido = 0;
            }
            axios.put('/articulo/cambiarComprometido',{
                'id': id,
                'comprometido' : this.comprometido
            }).then(function (response) {
                me.listarArticulo(1,'','sku','','',1);
            }).catch(function (error) {
                console.log(error);
            });
        },
        abrirModal2(modelo, accion, data = []) {
            switch (modelo) {
                case "articulo": {
                    switch (accion) {
                        case "visualizar": {
                            this.modal2 = 1;
                            this.tituloModal = "Detalle de artículo ";
                            this.idcategoria = data['idcategoria'];
                            this.codigo = data['codigo'];
                            this.sku = data['sku'];
                            this.terminado = data['terminado'];
                            this.largo = data['largo'];
                            this.alto = data['alto'];
                            this.metros_cuadrados = data['metros_cuadrados'];
                            this.espesor = data['espesor'];
                            this.precio_venta = data['precio_venta'];
                            this.ubicacion = data['ubicacion'];
                            this.stock = data['stock'];
                            this.descripcion= data['descripcion'];
                            this.observacion = data['observacion'];
                            this.origen = data['origen'];
                            this.contenedor  = data['contenedor'];
                            this.fecha_llegada = data['fecha_llegada'];
                            this.file = data['file'];
                            break;
                        }
                    }
                }
            }
            this.selectCategoria();
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal = "";
            this.idcategoria = 0;
            this.codigo = '';
            this.sku = '';
            this.terminado = '';
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.espesor = 0;
            this.ubicacion = '';
            this.stock = 1;
            this.descripcion= '';
            this.observacion = '';
            this.origen = '';
            this.fecha_llegada = '';
            this.file = '';
            this.errorArticulo = 0;
            this.imagenMinatura = '';
        },
        eliminarImagen(id,imagen){
            var arreglo = imagen.split("/",2);
            /* console.log("arreglo: " + arreglo[1]); */
            let file = arreglo[1];
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta de eliminar la imagen de este artículo?",
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
                    axios.put('/articulo/eliminarImg', {
                        'id' : id
                    }).then(function(response) {
                        me.imagenMinatura = 'images/null';
                        me.showElim = false;
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
        abrirModal3(){
            this.modal3 = 1;
            this.tituloModal = "Generar Reporte de artículos";
            this.selectBodega();
        },
        cerrarModal3(){
            this.modal3 = 0;
            this.tituloModal = "";
            this.arrayUbicaciones = [];
            this.bodegaReporte = "";
        },
        selectBodega(){
            let me=this;
            var url= '/articulo/selectBodega';
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayUbicaciones = respuesta.bodegas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        listarExcel(bodega){
            window.open('/articulo/listarExcel?bodega=' + bodega);
            this.cerrarModal3();
        },
        listarExcelVendido(bodega){
            window.open('/articulo/listarExcelVenta?bodega=' + bodega);
            this.cerrarModal3();
        },
        editArticulo(data = []){
            this.selectCategoria();
            this.listado = 0;
            this.showNew = false;
            this.tituloModal = `Actualizar Artículo ${ data['sku'] } - ${ data['codigo'] }`;
            this.articulo_id = data['id'];
            this.idcategoria = data['idcategoria'];
            this.codigo = data['codigo'];
            this.sku = data['sku'];
            this.terminado = data['terminado'];
            this.largo = data['largo'];
            this.alto = data['alto'];
            this.metros_cuadrados = data['metros_cuadrados'];
            this.espesor = data['espesor'];
            this.precio_venta = data['precio_venta'];
            this.ubicacion = data['ubicacion'];
            this.stock = data['stock'];
            this.descripcion= data['descripcion'];
            this.observacion = data['observacion'];
            this.origen = data['origen'];
            this.contenedor = data['contenedor'];
            this.fecha_llegada = data['fecha_llegada'];
            this.estado = data['condicion'];
            this.comprometido = data['comprometido'];
            this.usuario = data['usuario'];
            this.isEdition = true;

            if(this.comprometido == 1){
                this.btnComprometido = true;
            }else{
                this.btnComprometido = false;
            }

            let hasImg = 'images/' + data['file'];

            if(data['file']){
                this.showElim = true;
                this.imagenMinatura = 'images/' + data['file'];
                this.remoFile = false;
            }else{
                this.showElim = false;
                this.imagenMinatura = 'images/null';
                this.remoFile = false;
            }

        },
        closeEdit(){
            let pagec = this.pagination.current_page;
            this.showNew = true;
            this.listado = 1;
            this.tituloModal = "";
            this.idcategoria = 0;
            this.codigo = '';
            this.sku = '';
            this.terminado = '';
            this.largo = 0;
            this.alto = 0;
            this.metros_cuadrados = 0;
            this.espesor = 0;
            this.precio_venta  = 0;
            this.ubicacion = '';
            this.stock = 1;
            this.descripcion= '';
            this.observacion = '';
            this.origen = '';
            this.contenedor  = '';
            this.fecha_llegada = '';
            this.file = '';
            this.errorArticulo = 0;
            this.imagenMinatura = '';
            this.btnComprometido = '';
            this.comprometido = 0;
            this.usuario = '';
            this.isEdition = false;
            this.file = "";
            this.showElim = false;
            this.tituloModal = "";
            this.listarArticulo(pagec,this.buscar,this.criterio,this.bodega,this.acabado,this.estadoArt);
        }
    },
    mounted() {
        this.listarArticulo(1,this.buscar, this.criterio,this.bodega,this.acabado,this.estadoArt);
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
    .imgcenter {
        display:flex;
        margin:0 auto;
    }
    .modal-body{
        height: 550px;
        width: 100%;
        overflow-y: auto;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 13px;
        line-height: 1.42857;
    }
</style>

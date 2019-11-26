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
          <i class="fa fa-align-justify"></i> CRM
          <button v-if="btnNewTask" type="button" @click="abrirModal('registrar')" class="btn btn-sm btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nueva Tarea
          </button>

        </div>
        <!-- Listado -->
        <template v-if="listado==1">
            <div class="card-body">
                <div class="form-inline">
                    <div class="form-group mb-2 col-12">
                        <div class="input-group">
                            <select class="form-control mb-1" v-model="criterio">
                                <option value="fecha">Fecha</option>
                                <option value="idcliente">Cliente</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarTarea(1,buscar,criterio,estadoTask)" class="form-control mb-1" placeholder="Texto a buscar">
                        </div>
                        <div class="input-group">
                            <select class="form-control mb-1" v-model="estadoTask">
                                <option value="">Pendiente</option>
                                <option value="1">Completado</option>
                                <option value="2">Cancelado</option>
                            </select>
                            <button type="submit" @click="listarTarea(1,buscar,criterio,estadoTask)" class="btn btn-primary mb-1"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Fecha</th>
                                <th>Tarea</th>
                                <th>Tipo de cliente</th>
                                <th>Estado</th>
                                <th>Vendedor</th>
                            </tr>
                        </thead>
                        <tbody v-if="arrayTarea.length" class="text-center">
                            <tr v-for="tarea in arrayTarea" :key="tarea.id">
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" @click="verTarea(tarea.idcliente)">
                                        <i class="icon-eye"></i>
                                    </button>&nbsp;

                                    <template v-if="tarea.estado != 2">
                                        <button type="button" @click="abrirModal('actualizar',tarea)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </template>

                                    <template v-if="tarea.estado == 0 ">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarTarea(tarea.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <!-- <template v-else>
                                    </template> -->
                                </td>
                                <td v-text="tarea.cliente"></td>
                                <td v-text="tarea.telefono"></td>
                                <td>
                                    <div v-if="tarea.fecha < dateAct">
                                        <span class="badge badge-pill badge-danger">{{ convertDate(tarea.fecha) }}</span>
                                    </div>
                                    <div v-else-if="tarea.fecha == dateAct">
                                        <span class="badge badge-pill badge-warning">{{ convertDate(tarea.fecha) }}</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-pill badge-secondary">{{ convertDate(tarea.fecha) }}</span>
                                    </div>
                                </td>
                                <td v-text="tarea.descripcion"></td>
                                <td v-text="tarea.tipo"></td>
                                <td>
                                    <div v-if="tarea.estado == 1">
                                        <span class="badge badge-pill badge-success">Completado</span>
                                    </div>
                                    <div v-else-if="tarea.estado == 0">
                                        <span class="badge badge-pill badge-warning">Pendiente</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-pill badge-danger">Cancelado</span>
                                    </div>
                                </td>
                                <td v-text="tarea.usuario"></td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <strong>Aún no tienes tareas dadas de alta...</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio,estadoTask)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio,estadoTask)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, criterio,estadoTask)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </template>
        <!-- Fin Listado -->

        <!-- Detalle -->
        <template v-else-if="listado==0">
            <div class="card-body">
                <!-- INF CLIENTE -->
                <div class="form-group row">
                    <div class="col-md-3 text-center">
                        <div class="form-group">
                            <h1 for="" class="float-left"><strong v-text="cliente"></strong></h1>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2 text-center sinpadding" v-if="telefono_cliente">
                        <div class="form-group">
                            <label for=""><strong>Teléfono</strong></label>
                            <h6 for=""><strong v-text="telefono_cliente"></strong></h6>
                        </div>
                    </div>&nbsp;
                     <div class="col-md-2 text-center sinpadding" v-if="tipo_cliente">
                        <div class="form-group">
                            <label for=""><strong>Tipo de cliente</strong></label>
                            <h6 for=""><strong v-text="tipo_cliente"></strong></h6>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2 text-center sinpadding" v-if="rfc_cliente">
                        <div class="form-group">
                            <label for=""><strong>RFC</strong></label>
                            <h6 for=""><strong v-text="rfc_cliente"></strong></h6>
                        </div>
                    </div>&nbsp;
                    <div class="col-md-2 text-center sinpadding" v-if="contacto_cliente">
                        <div class="form-group">
                            <label for=""><strong>Contacto</strong></label>
                            <h6 for=""><strong> {{contacto_cliente}} | {{telcontacto_cliente}}</strong></h6>
                        </div>
                    </div>&nbsp;

                    <div class="col-md-3 text-center sinpadding">
                        <div class="form-group">
                            <label for=""><strong>Observaciones</strong></label>
                            <h6 for=""><strong> {{obs_cliente}}</strong></h6>
                        </div>
                    </div>&nbsp;
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-5 order-1 order-md-1">
                        <h4>Siguiente Tarea</h4>&nbsp;
                    </div>
                    <div class="col-md-5 order-3 order-md-2">
                        <h4 class="ml-2">Ultimas Ventas: </h4>
                    </div>&nbsp;
                    <div class="col-md-5 order-2 order-md-3 ml-3 pt-3 caja2" v-if="nextTask.length">
                        <div class="row" v-for="nexttask in nextTask" :key="nexttask.id">
                            <div class="col-md">
                                <h4 v-text="nexttask.clase"></h4>
                                <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(nexttask.fecha) }}</small></p>
                            </div>
                            <div class="col-md">
                                <button type="button" class="btn btn-sm btntask float-right" @click="UpdateTask('nextTask',nexttask)">
                                        <i class="fa fa-pencil"></i>
                                    </button>&nbsp;
                                <template v-if="nexttask.estado == 0 ">
                                    <button type="button" class="btn btn-sm btntask float-right" @click="desactivarTareaDet(nexttask.id)">
                                        <i class="fa fa-trash"></i>
                                    </button>&nbsp;
                                </template>
                            </div>
                            <div class="col-md-12">
                                <p v-text="nexttask.descripcion"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 ml-3 pt-3 caja2 order-2 order-md-3" v-else>
                        <div class="row">
                            <div class="col-md">
                                <h4>Sin tareas pendientes o asignadas...</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md text-center order-4 order-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm table-hover table-responsive-xl">
                                <thead>
                                    <tr>
                                        <th>Comprobante</th>
                                        <th>Atendió</th>
                                        <th>Cliente</th>
                                        <th>No° Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>100% Pagado</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayVentasT.length">
                                    <tr v-for="venta in arrayVentasT" :key="venta.id">
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm" @click="pdfVenta(venta.id)">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </button>&nbsp;
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td>{{ convertDateVenta(venta.fecha_hora) }}</td>
                                        <td v-text="venta.total"></td>
                                        <td v-if="venta.pagado">
                                            <toggle-button :value="true" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                        </td>
                                        <td v-else>
                                            <toggle-button :value="false" :labels="{checked: 'Si', unchecked: 'No'}" disabled />
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <strong>Aún no tienes ventas con este cliente...</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>&nbsp;
                </div>
                <div class="form-group row">
                    <!-- Comentarios -->
                    <div class="col-md-6">
                        <div class="page-header">
                            <h3 id="timeline">Comentarios de {{ cliente }} &nbsp;
                                <button type="button" class="btn btn-primary btn-circle" @click="UpdateTask('newComment')">
                                    <i class="fa fa-plus-circle"></i>
                                </button>&nbsp;
                            </h3>
                            <hr>
                        </div>
                        <div class="divtask" v-if="arrayCommentT.length">
                            <ul class="row" v-for="comment in arrayCommentT" :key="comment.id">
                                <li class="col-md-6" style="list-style:none;">
                                    <div class="form-group">
                                        <div class="col-md my-3 pt-3 caja">
                                            <div class="row">
                                                <div class="col-md">
                                                    <h4 v-text="comment.clase"></h4>
                                                    <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(comment.fecha) }}</small></p>
                                                </div>
                                                <div class="col-md">
                                                    <button type="button" class="btn btn-sm btntask float-right" @click="UpdateTask('comment',comment)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>&nbsp;
                                                    <template v-if="comment.estado == 0 ">
                                                        <button type="button" class="btn btn-sm btntask float-right" @click="desactivarComentario(comment.id)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>&nbsp;
                                                    </template>
                                                </div>
                                                <div class="col-md-12">
                                                    <p v-text="comment.descripcion"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <h5>Sin Comentaríos...</h5>
                        </div>
                    </div>
                    <!-- Historial -->
                    <div class="col-md-6">
                        <div class="page-header">
                            <h3 id="timeline">Historial de tareas {{ cliente }}
                                &nbsp;
                                <button type="button" class="btn btn-primary btn-circle" @click="UpdateTask('newTask')">
                                    <i class="fa fa-plus-circle"></i>
                                </button>&nbsp;
                            </h3>
                            <hr>
                        </div>
                        <div class="divtask">
                            <ul class="timeline">
                                <li v-for="tarea in arrayTareaT" :key="tarea.id">
                                    <template v-if="tarea.estado == 0 && tarea.clase == 'Llamada'">
                                        <div class="timeline-badge warning"><i class="fa fa-phone"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <h4 class="timeline-title">{{ tarea.clase }}</h4>
                                                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(tarea.fecha) }}</small></p>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-sm btntask float-right">
                                                            <i class="fa fa-pencil" @click="UpdateTask('arrayTareaT',tarea)"></i>
                                                        </button>&nbsp;
                                                        <template v-if="tarea.estado == 0 ">
                                                            <button type="button" class="btn btn-sm btntask float-right" @click="desactivarTareaDet(tarea.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>&nbsp;
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-body">
                                                <p v-text="tarea.descripcion"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="tarea.estado == 1 && tarea.clase == 'Llamada'">
                                        <div class="timeline-badge success"><i class="fa fa-phone"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <h4 class="timeline-title">{{ tarea.clase }}</h4>
                                                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(tarea.fecha) }}</small></p>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-sm btntask float-right">
                                                            <i class="fa fa-pencil" @click="UpdateTask('arrayTareaT',tarea)"></i>
                                                        </button>&nbsp;
                                                        <template v-if="tarea.estado == 0 ">
                                                            <button type="button" class="btn btn-sm btntask float-right" @click="desactivarTareaDet(tarea.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>&nbsp;
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-body">
                                                <p v-text="tarea.descripcion"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="tarea.estado == 2 && tarea.clase == 'Llamada'">
                                        <div class="timeline-badge danger"><i class="fa fa-phone"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title"><del>{{ tarea.clase }}</del></h4>
                                                <p><small class="text-muted"><i class="fa fa-clock-o"></i><del> {{ convertDate(tarea.fecha) }}</del></small>
                                                <span class="badge badge-pill badge-danger float-right">CANCELADO</span></p>
                                            </div>
                                            <div class="timeline-body">
                                                <p><del>{{tarea.descripcion}}</del></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="tarea.estado == 0 && tarea.clase == 'Nota'">
                                        <div class="timeline-badge warning"><i class="fa fa-comment"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <h4 class="timeline-title">{{ tarea.clase }}</h4>
                                                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(tarea.fecha) }}</small></p>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-sm btntask float-right">
                                                            <i class="fa fa-pencil" @click="UpdateTask('arrayTareaT',tarea)"></i>
                                                        </button>&nbsp;
                                                        <template v-if="tarea.estado == 0 ">
                                                            <button type="button" class="btn btn-sm btntask float-right" @click="desactivarTareaDet(tarea.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>&nbsp;
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-body">
                                                <p v-text="tarea.descripcion"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="tarea.estado == 1 && tarea.clase == 'Nota'">
                                        <div class="timeline-badge success"><i class="fa fa-comment"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <h4 class="timeline-title">{{ tarea.clase }}</h4>
                                                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ convertDate(tarea.fecha) }}</small></p>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-sm btntask float-right">
                                                            <i class="fa fa-pencil" @click="UpdateTask('arrayTareaT',tarea)"></i>
                                                        </button>&nbsp;
                                                        <template v-if="tarea.estado == 0 ">
                                                            <button type="button" class="btn btn-sm btntask float-right" @click="desactivarTareaDet(tarea.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>&nbsp;
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-body">
                                                <p v-text="tarea.descripcion"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="tarea.estado == 2 && tarea.clase == 'Nota'">
                                        <div class="timeline-badge danger"><i class="fa fa-comment"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title"><del>{{ tarea.clase }}</del></h4>
                                                <p><small class="text-muted"><i class="fa fa-clock-o"></i><del> {{ convertDate(tarea.fecha) }}</del></small>
                                                <span class="badge badge-pill badge-danger float-right">CANCELADO</span></p>
                                            </div>
                                            <div class="timeline-body">
                                                <p><del>{{tarea.descripcion}}</del></p>
                                            </div>
                                        </div>
                                    </template>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group row border">

                </div> -->
                <div class="form-group row">
                    <div class="col-md-12 float-right">
                        <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
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
            <div class="modal-content content-task">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-5 text-center">
                            <div class="form-group" v-if="isEdition == false">
                                <label for=""><strong>Cliente (*)</strong></label>
                                    <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Buscar clientes..." :onChange="getDatosCliente">
                                    </v-select>
                            </div>
                            <div class="form-group"  v-else>
                                <label for=""><strong>Cliente</strong></label>
                                <h4 for=""><strong v-text="cliente"></strong></h4>
                            </div>
                        </div>&nbsp;
                        <template v-if="idcliente">
                            <div class="col-md-3 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Tipo de cliente</strong></label>
                                    <h6 for=""><strong v-text="tipo_cliente"></strong></h6>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="form-group">
                                    <label for=""><strong>RFC</strong></label>
                                    <h6 for=""><strong v-text="rfc_cliente"></strong></h6>
                                </div>
                            </div>
                        </template>
                        <input type="number" hidden :value="getFechaCode" class="form-control col-md"/>
                    </div>
                    <div class="form-group row">
                        <template v-if="tipo == 'Llamada'">
                            <div class="col-md-3 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Teléfono</strong></label>
                                    <h6 for=""><strong v-text="telefono_cliente"></strong></h6>
                                </div>
                            </div>
                            <template v-if="contacto_cliente">
                                <div class="col-md-3 text-center">
                                    <div class="form-group">
                                        <label for=""><strong>Contacto</strong></label>
                                        <h6 for=""><strong v-text="contacto_cliente"></strong></h6>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="form-group">
                                        <label for=""><strong>Teléfono de Contacto</strong></label>
                                        <h6 for=""><strong v-text="telcontacto_cliente"></strong></h6>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                            </template>
                        </template>
                        <div class="col-md-3 text-center">
                            <div class="form-group">
                                <label class="form-control-label" for="text-input"><strong>Fecha</strong></label>
                                <input type="date" v-model="fecha" class="form-control col-md" placeholder="Fecha para realizar"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <template v-if="isComment">
                        </template>
                        <template v-else>
                            <div class="col-md-2 text-center">
                                <div class="form-group">
                                    <label for=""><strong>Tipo</strong></label>
                                    <select class="form-control col-md" v-model="tipo">
                                        <option value='' disabled>Seleccione(*)</option>
                                        <option value="Nota">Nota</option>
                                        <option value="Llamada">Llamada</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        <div class="col-md-8 text-center">
                            <label for=""><strong>Notas:</strong></label>
                            <textarea class="form-control rounded-0" rows="3" maxlength="254" v-model="descripcion"></textarea>
                        </div>
                        <template v-if="isEdition && isComment==0">
                            <div class="col-md-2 text-center">
                                <label for=""><strong>Tarea Completada:</strong></label>&nbsp;
                                <template v-if="tipoAccion == 2">
                                    <toggle-button @change="cambiarEstadoTarea(id)" v-model="btnComp" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                                </template>
                                <template v-else>
                                    <toggle-button @change="cambiarEstadoTareaDet(id)" v-model="btnComp" :sync="true" :labels="{checked: 'Si', unchecked: 'No'}" />
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <div v-show="errorTarea" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjTarea" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="d-block d-sm-block d-md-none">
                    <div class="float-right d-block d-sm-block d-md-none">
                        <button type="button" v-if="cerrarDet!=1" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="cerrarDet==1" class="btn btn-secondary" @click="cerrarModalDet()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTarea()">Guardar</button>
                        <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="registrarTareaDet()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTarea()">Actualizar</button>
                        <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="actualizarTareaDet()">Actualizar</button>
                    </div>
                </div>
                <div class="modal-footer d-none d-sm-none d-md-block">
                    <button type="button" v-if="cerrarDet!=1" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="cerrarDet==1" class="btn btn-secondary" @click="cerrarModalDet()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTarea()">Guardar</button>
                    <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="registrarTareaDet()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTarea()">Actualizar</button>
                    <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="actualizarTareaDet()">Actualizar</button>
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
import ToggleButton from 'vue-js-toggle-button';
Vue.component("Lightbox",VueLightbox);
Vue.use(ToggleButton);
export default {
    data() {
        return {
            tarea_id: 0,
            nombre : "",
            descripcion : "",
            tipo : "",
            fecha : "",
            estado : 0,
            user: '',
            idcliente: 0,
            cliente: '',
            rfc_cliente : "",
            tipo_cliente : "",
            telefono_cliente : "",
            contacto_cliente : "",
            telcontacto_cliente : "",
            obs_cliente: "",
            arrayTarea : [],
            arrayCliente : [],
            listado : 1,
            modal: 0,
            modal2: 0,
            ind : '',
            tituloModal: "",
            tipoAccion: 0,
            errorTarea: 0,
            errorMostrarMsjTarea: [],
            pagination : {
                'total'        : 0,
                'current_page' : 0,
                'per_page'     : 0,
                'last_page'    : 0,
                'from'         : 0,
                'to'           : 0,
            },
            offset : 3,
            criterio : 'idcliente',
            buscar : '',
            areaUs : "",
            estadoVn : "",
            CodeDate : "",
            vig : "",
            obsEditable : 0,
            dateAct : "",
            btnComp : false,
            isEdition : false,
            estadoTask : '',
            arrayTareaT : [],
            arrayVentasT : [],
            btnNewTask : 1,
            nextTask : [],
            cerrarDet : 0,
            isComment : 0,
            arrayCommentT : []
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
        getFechaCode : function(){
            let me = this;
            let date = "";
            moment.locale('es');
            date = moment().format('YYMMDD');
            me.CodeDate = moment().format('YYMMDD');
            me.dateAct = moment().format('YYYY-MM-DD');

            return date;
        },
    },
    methods: {
        listarTarea(page,buscar,criterio,estado){
            let me=this;
            me.btnNewTask = 1;
            var url= '/tarea?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&estado='+ estado;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayTarea = respuesta.tareas.data;
                me.pagination= respuesta.pagination;
                /* console.log(me.arrayTarea); */
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        convertDate(date){
            let me=this;
            var datec = moment(date).format('MMM DD YY');
            /* console.log(datec); */
            return datec;
        },
        convertDateVenta(date){
            let me=this;
            var datec = moment(date).format('MMM DD YYYY HH:mm:ss');
            /* console.log(datec); */
            return datec;
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
            me.idcliente = val1.id
            me.rfc_cliente =  val1.rfc;
            me.tipo_cliente = val1.tipo;
            me.telefono_cliente = val1.telefono;
            me.contacto_cliente = val1.company;
            me.telcontacto_cliente = val1.tel_company;
            me.obs_cliente = val1.observacion;
        },
        cambiarPagina(page,buscar,criterio,estado){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarTarea(page,buscar,criterio,estado);
        },
        cerrarModal(){
            this.modal = 0;
            this.buscar = "";
            this.idcliente = "";
            this.cliente = "";
            this.descripcion = "";
            this.fecha = "";
            this.nombre = "";
            this.tipo_cliente = "";
            this.rfc_cliente = "";
            this.telefono_cliente = "";
            this.contacto_cliente = "";
            this.telcontacto_cliente = "";
            this.obs_cliente = "";
            this.tipo = "";
            this.estado = 0;
            this.btnComp = false;
            this.isEdition = false;
        },
        cerrarModalDet(){
            this.modal = 0;
            this.cerrarDet = 0;
            this.btnComp = false;
            this.isComment = 0;
            this.verTarea(this.idcliente);
        },
        abrirModal(accion, data = []){
            this.listado = 1;
            switch (accion) {
                case "registrar": {
                    this.modal = 1;
                    this.tituloModal = "Tarea Nueva";
                    this.tipoAccion = 1;
                    break;
                }
                case "actualizar": {
                    this.modal = 1;
                    this.id = data['id'];
                    this.cliente = data['cliente'];
                    this.tituloModal = "Modificar Tarea Para " + this.cliente;
                    this.tipoAccion = 2;
                    this.idcliente = data['idcliente'];
                    this.rfc_cliente = data['rfc'];
                    this.tipo_cliente = data['tipo'];
                    this.contacto_cliente = data['company'];
                    this.telcontacto_cliente = data['tel_company'];
                    this.telefono_cliente = data['telefono'];
                    this.obs_cliente = data['observacion'];
                    this.nombre = data['nombre'];
                    this.descripcion = data['descripcion'];
                    this.tipo = data['clase'];
                    this.fecha = data['fecha'];
                    this.estado =data['estado'];
                    this.isEdition = true;

                    if(this.estado){
                        this.btnComp = true;
                    }

                    break;
                }
            }
        },
        validarTarea() {
            let me = this;
            var art;

            me.errorTarea = 0;
            me.errorMostrarMsjTarea = [];


            if (me.idcliente==0) me.errorMostrarMsjTarea.push("Seleccione un cliente");
            if (me.fecha == '') me.errorMostrarMsjTarea.push("Seleccione la fecha para la tarea");
            if(me.fecha < me.dateAct) me.errorMostrarMsjTarea.push("La fecha de la tarea no puede ser menos a la fecha actual");
            if (me.errorMostrarMsjTarea.length) me.errorTarea = 1;

            return me.errorTarea;
        },
        registrarTarea(){
            if (this.validarTarea()) {
                return;
            }
            let me = this;
            var name = "T-".concat(me.CodeDate,"-",me.tipo);
            /* console.log(name); */
            me.nombre = name;
            axios.post('/tarea/registrar',{
                'idcliente': this.idcliente,
                'nombre': this.nombre,
                'descripcion' : this.descripcion,
                'tipo' : this.tipo,
                'fecha' : this.fecha
            }).then(function(response) {
                me.cerrarModal();
                me.listarTarea(1,'','idcliente','');
                me.idcliente = 0;
                me.nombre = "";
                me.descripcion ="";
                me.fecha = "";
                me.tipo = "";
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        registrarTareaDet(){
            if (this.validarTarea()) {
                return;
            }
            let me = this;
            var name = "T-".concat(me.CodeDate,"-",me.tipo);
            /* console.log(name); */
            me.nombre = name;
            axios.post('/tarea/registrar',{
                'idcliente': this.idcliente,
                'nombre': this.nombre,
                'descripcion' : this.descripcion,
                'tipo' : this.tipo,
                'fecha' : this.fecha
            }).then(function(response) {
                me.verTarea(me.idcliente);
                me.cerrarModalDet();
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        actualizarTarea(){
            if (this.validarTarea()) {
                return;
            }
            let me = this;
            axios.put('/tarea/actualizar',{
                'id' : this.id,
                'nombre' : this.nombre,
                'idcliente': this.idcliente,
                'nombre': this.nombre,
                'descripcion' : this.descripcion,
                'tipo' : this.tipo,
                'fecha' : this.fecha
            }).then(function(response) {
                me.cerrarModal();
                me.listarTarea(1,'','idcliente','');
                me.idcliente = 0;
                me.nombre = "";
                me.descripcion ="";
                me.fecha = "";
                me.tipo = "";
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        actualizarTareaDet(){
            if (this.validarTarea()) {
                return;
            }
            let me = this;
            axios.put('/tarea/actualizar',{
                'id' : this.id,
                'nombre' : this.nombre,
                'idcliente': this.idcliente,
                'nombre': this.nombre,
                'descripcion' : this.descripcion,
                'tipo' : this.tipo,
                'fecha' : this.fecha
            }).then(function(response) {
                me.verTarea(me.idcliente);
                me.cerrarModalDet();
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        cambiarEstadoTarea(id){
            let me = this;
            if(me.btnComp == true){
                me.estado = 1;
            }else{
                me.estado = 0;
            }
            axios.put('/tarea/completar',{
                'id': id,
                'estado' : this.estado
            }).then(function (response) {
                me.listarTarea(1,'','idcliente','');
            }).catch(function (error) {
                console.log(error);
            });
        },
        cambiarEstadoTareaDet(id){
            let me = this;
            if(me.btnComp == true){
                me.estado = 1;
            }else{
                me.estado = 0;
            }
            axios.put('/tarea/completar',{
                'id': id,
                'estado' : this.estado
            }).then(function (response) {
                me.verTarea(me.idcliente);
            }).catch(function (error) {
                console.log(error);
            });
        },
        desactivarTarea(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de anular esta tarea?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/tarea/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarTarea(1,'','idcliente','');
                        swal.fire(
                        'Anulado!',
                        'La tarea ha sido anulado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        desactivarTareaDet(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de anular esta tarea?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/tarea/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.verTarea(me.idcliente);
                        swal.fire(
                            'Anulado!',
                            'La tarea ha sido anulado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        desactivarComentario(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Esta seguro de eliminar esta comentario?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let me = this;
                    axios.put('/tarea/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.verTarea(me.idcliente);
                        swal.fire(
                            'Eliminado!',
                            'El comentario ha sido eliminado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else if (result.dismiss === swal.DismissReason.cancel){
                }
            })
        },
        ocultarDetalle(){
            this.listado = 1;
            this.cliente = 0;
            this.errorTarea =0;
            this.errorMostrarMsjTarea = [];
            this.arrayTareaT = [];
            this.nextTask = [];
            this.arrayVentasT = [];
            this.arrayCommentT = [];
            this.idcliente = '';
            this.cliente = '';
            this.rfc_cliente = '';
            this.tipo_cliente = '';
            this.telefono_cliente = '';
            this.contacto_cliente = '';
            this.telcontacto_cliente ='';
            this.obs_cliente = '';
            this.btnNewTask = 1;
            this.cerrarDet = 0;
            this.isEdition = false;
            this.nombre = "";
            this.descripcion = "";
            this.tipo = "";
            this.fecha = "";
            this.listarTarea(1,'','idcliente','');
            this.isComment = 0;
        },
        verTarea(idcliente){
            let me = this;
            me.listado = 0;
            me.btnNewTask = 0;
            var url= '/tarea/obtenerTareas?idcliente=' + idcliente;
            axios.get(url).then(function (response){
                var respuesta= response.data;
                me.arrayTareaT = respuesta.tareas.data;
                me.nextTask = respuesta.siguiente.data;
                me.arrayCommentT = respuesta.comentarios.data;
                me.idcliente = me.arrayTareaT[0]['idcliente'];
                me.cliente = me.arrayTareaT[0]['cliente'];
                me.rfc_cliente = me.arrayTareaT[0]['rfc'];
                me.tipo_cliente = me.arrayTareaT[0]['tipo'];
                me.telefono_cliente = me.arrayTareaT[0]['telefono'];
                me.contacto_cliente = me.arrayTareaT[0]['company'];
                me.telcontacto_cliente = me.arrayTareaT[0]['tel_company'];
                me.obs_cliente = me.arrayTareaT[0]['observacion'];
            })
            .catch(function (error) {
                console.log(error);
            });

            var url= '/venta/obtenerVentasCliente?idcliente=' + idcliente;
            axios.get(url).then(function (response){
                var respuesta= response.data;
                me.arrayVentasT = respuesta.ventas.data;
               /*  console.log(me.arrayVentasT); */
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        pdfVenta(id){
            window.open('/venta/pdf/'+id);
        },
        UpdateTask(accion, data = []){
            switch (accion) {
                case "nextTask": {
                    this.modal = 1;
                    this.id = data['id'];
                    this.cliente = data['cliente'];
                    this.tituloModal = "Modificar Tarea Para " + this.cliente;
                    this.tipoAccion = 3;
                    this.idcliente = data['idcliente'];
                    this.rfc_cliente = data['rfc'];
                    this.tipo_cliente = data['tipo'];
                    this.contacto_cliente = data['company'];
                    this.telcontacto_cliente = data['tel_company'];
                    this.telefono_cliente = data['telefono'];
                    this.obs_cliente = data['observacion'];
                    this.nombre = data['nombre'];
                    this.descripcion = data['descripcion'];
                    this.tipo = data['clase'];
                    this.fecha = data['fecha'];
                    this.estado =data['estado'];
                    this.isEdition = true;
                    this.cerrarDet = 1;
                    this.isComment = 0;

                    if(this.estado){
                        this.btnComp = true;
                    }

                    break;
                }
                case "arrayTareaT": {
                    this.modal = 1;
                    this.id = data['id'];
                    this.cliente = data['cliente'];
                    this.tituloModal = "Modificar Tarea Para " + this.cliente;
                    this.tipoAccion = 3;
                    this.idcliente = data['idcliente'];
                    this.rfc_cliente = data['rfc'];
                    this.tipo_cliente = data['tipo'];
                    this.contacto_cliente = data['company'];
                    this.telcontacto_cliente = data['tel_company'];
                    this.telefono_cliente = data['telefono'];
                    this.obs_cliente = data['observacion'];
                    this.nombre = data['nombre'];
                    this.descripcion = data['descripcion'];
                    this.tipo = data['clase'];
                    this.fecha = data['fecha'];
                    this.estado =data['estado'];
                    this.isEdition = true;
                    this.cerrarDet = 1;

                    if(this.estado){
                        this.btnComp = true;
                    }

                    break;
                }
                case "newTask" : {
                    this.modal = 1;
                    this.tipoAccion = 4;
                    this.cerrarDet = 1;
                    this.tituloModal = "Nueva Tarea Para " + this.cliente;
                    this.isEdition = true;
                    this.nombre = "";
                    this.descripcion = "";
                    this.tipo = "";
                    this.fecha = "";
                    this.isComment = 0;
                    break;
                }
                case "newComment" : {
                    this.modal = 1;
                    this.tipoAccion = 4;
                    this.cerrarDet = 1;
                    this.tituloModal = "Nuevo Comentario Para " + this.cliente;
                    this.isEdition = true;
                    this.nombre = "";
                    this.descripcion = "";
                    this.tipo = "Comentario";
                    this.fecha = "";
                    this.isComment = 1;
                    break;
                }
                case "comment": {
                    this.modal = 1;
                    this.id = data['id'];
                    this.cliente = data['cliente'];
                    this.tituloModal = "Modificar comentario de " + this.cliente;
                    this.tipoAccion = 3;
                    this.idcliente = data['idcliente'];
                    this.rfc_cliente = data['rfc'];
                    this.tipo_cliente = data['tipo'];
                    this.contacto_cliente = data['company'];
                    this.telcontacto_cliente = data['tel_company'];
                    this.telefono_cliente = data['telefono'];
                    this.obs_cliente = data['observacion'];
                    this.nombre = data['nombre'];
                    this.descripcion = data['descripcion'];
                    this.tipo = data['clase'];
                    this.fecha = data['fecha'];
                    this.estado =data['estado'];
                    this.isEdition = true;
                    this.cerrarDet = 1;
                    this.isComment = 1;

                    if(this.estado){
                        this.btnComp = true;
                    }

                    break;
                }
            }
        },
        mostrarDetalle(){
            this.listado = 0;
        }
    },
    mounted() {
        this.listarTarea(1,this.buscar, this.criterio,this.estadoTask);
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
    input[type="number"]
    {
        -webkit-appearance: textfield !important;
        margin: 0;
    }
    .content-task{
        height: 480px !important;
    }
    .sinpadding [class*="col-"] {
        padding: 0;
    }
    .timeline {
        list-style: none;
        padding: 20px 0 20px;
        position: relative;
    }
    .timeline:before {
        top: 0;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }
    .timeline > li {
        margin-bottom: 20px;
        position: relative;
    }
    .timeline > li:before,
    .timeline > li:after {
        content: " ";
        display: table;
    }
    .timeline > li:after {
        clear: both;
    }
    .timeline > li:before,
    .timeline > li:after {
        content: " ";
        display: table;
    }
    .timeline > li:after {
        clear: both;
    }
    .timeline > li > .timeline-panel {
        width: 46%;
        float: left;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 20px;
        position: relative;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }
    .timeline > li > .timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -15px;
        display: inline-block;
        border-top: 15px solid transparent;
        border-left: 15px solid #ccc;
        border-right: 0 solid #ccc;
        border-bottom: 15px solid transparent;
        content: " ";
    }
    .timeline > li > .timeline-panel:after {
        position: absolute;
        top: 27px;
        right: -14px;
        display: inline-block;
        border-top: 14px solid transparent;
        border-left: 14px solid #fff;
        border-right: 0 solid #fff;
        border-bottom: 14px solid transparent;
        content: " ";
    }
    .timeline > li > .timeline-badge {
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 1.4em;
        text-align: center;
        position: absolute;
        top: 16px;
        left: 50%;
        margin-left: -25px;
        background-color: #999999;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }
    .timeline > li.timeline-inverted > .timeline-panel {
        float: right;
    }
    .timeline > li.timeline-inverted > .timeline-panel:before {
        border-left-width: 0;
        border-right-width: 15px;
        left: -15px;
        right: auto;
    }
    .timeline > li.timeline-inverted > .timeline-panel:after {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }
    .timeline-badge.primary {
        background-color: #2e6da4 !important;
    }
    .timeline-badge.success {
        background-color: #3f903f !important;
    }
    .timeline-badge.warning {
        background-color: #f0ad4e !important;
    }
    .timeline-badge.danger {
        background-color: #d9534f !important;
    }
    .timeline-badge.info {
        background-color: #5bc0de !important;
    }
    .timeline-title {
        margin-top: 0;
        color: inherit;
    }
    .timeline-body > p,
    .timeline-body > ul {
        margin-bottom: 0;
    }
    .timeline-body > p + p {
        margin-top: 5px;
    }
    @media (max-width: 767px) {
        ul.timeline:before {
            left: 40px;
        }

        ul.timeline > li > .timeline-panel {
            width: calc(100% - 90px);
            width: -moz-calc(100% - 90px);
            width: -webkit-calc(100% - 90px);
        }

        ul.timeline > li > .timeline-badge {
            left: 15px;
            margin-left: 0;
            top: 16px;
        }

        ul.timeline > li > .timeline-panel {
            float: right;
        }

        ul.timeline > li > .timeline-panel:before {
            border-left-width: 0;
            border-right-width: 15px;
            left: -15px;
            right: auto;
        }

        ul.timeline > li > .timeline-panel:after {
            border-left-width: 0;
            border-right-width: 14px;
            left: -14px;
            right: auto;
        }
    }
    div.divtask{
        height: 400px;
        width: 100% !important;
        overflow-y: scroll;
        scrollbar-width: none;
    }
    .divtask::-webkit-scrollbar {
        display: none;
    }
    div.caja{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        height: auto !important;

    }
    div.caja2{
        /* box-shadow: inset 0 0 2px black; */
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        height: 150px;

    }
    button.btntask{
        background-color: transparent;
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

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    //LOGIN
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::get('/login', 'Auth\LoginController@showLoginForm');
});

Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard','DashboardController');
    Route::post('/notification/get', 'NotificationController@get');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');




    Route::group(['middleware'=>['Almacenero']],function(){
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::post('/articulo/registrarDetalle', 'ArticuloController@storeDetalle');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');


    });

    Route::group(['middleware'=>['Vendedor']],function(){

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');


    });

    Route::group(['middleware'=>['Administrador']],function(){

        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::post('/articulo/registrarDetalle', 'ArticuloController@storeDetalle');
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::put('/articulo/actualizarCorte', 'ArticuloController@updateCortado');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf');
        Route::get('/articulo/listarExcel','ArticuloController@listarExcel');
        Route::put('/articulo/cambiarComprometido', 'ArticuloController@cambiarComprometido');
        Route::get('/articulo/listarArticuloCotizado', 'ArticuloController@listarArticuloCotizado');
        Route::put('/articulo/eliminarImg', 'ArticuloController@eliminarImagen');
        Route::get('/articulo/selectBodega', 'ArticuloController@selectBodega');
        Route::get('/articulo/listarExcelVenta','ArticuloController@listarExcelVenta');
        Route::get('/articulo/getCodesSku','ArticuloController@getCodesSku');
        Route::get('/articulo/listByCategory','ArticuloController@listByCategory');
        Route::get('/articulo/listBySku','ArticuloController@listBySku');
        Route::get('/articulo/listarExcelFiltros','ArticuloController@listarExcelFiltros');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::put('/cliente/desactivar', 'ClienteController@desactivarCliente');
        Route::put('/cliente/activar', 'ClienteController@activarCliente');
        Route::put('/cliente/filesupplo', 'ClienteController@filesUppload');
        Route::get('/cliente/getDocs', 'ClienteController@getDocs');
        Route::put('/cliente/eliminarDoc', 'ClienteController@eliminarDoc');

        Route::post('/cliente/crearComment', 'ClienteController@crearComment');
        Route::get('/cliente/getComments', 'ClienteController@getComments');
        Route::put('/cliente/editComment','ClienteController@editComment');
        Route::put('/cliente/deleteComment','ClienteController@deleteComment');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/selectUsuario', 'UserController@selectUsuario');
        Route::get('/user/selectUsuarioAct', 'UserController@selectUsuarioAct');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/nextNum','IngresoController@getLastNum');
        Route::get('/ingreso/pdf/{id}','IngresoController@pdf');

        Route::get('/venta','VentaController@index');
        Route::get('/ventaInv','VentaController@indexInvo');
        Route::post('/venta/registrar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerCabecera','VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles','VentaController@obtenerDetalles');
        Route::put('/articulo/actualizarCorte','ArticuloController@updateCortado');
        Route::get('/venta/pdf/{id}','VentaController@pdf');
        Route::post('/venta/cambiarEntrega','VentaController@cambiarEntrega');
        Route::post('/venta/cambiarEntregaParcial','VentaController@cambiarEntregaParcial');
        Route::post('/venta/cambiarPagado','VentaController@cambiarPagado');
        Route::post('/venta/actualizarObservacion','VentaController@actualizarObservacion');
        Route::post('/venta/actualizarObservacionPriv','VentaController@actualizarObservacionPriv');
        Route::get('/venta/nextNum','VentaController@getLastNum');
        Route::get('/venta/obtenerVentasCliente','VentaController@obtenerVentasCliente');
        Route::put('/venta/cambiarFacturacion','VentaController@cambiarFacturacion');
        Route::put('/venta/cambiarFacturacionEnv','VentaController@cambiarFacturacionEnv');
        Route::get('/ventaDeposit','VentaController@indexDeposit');
        Route::put('/venta/autorizarEntrega','VentaController@autorizarEntrega');

        Route::get('/entrega','VentaController@indexEntregas');
        Route::get('/entrega/pdf/{id}','VentaController@pdfEntrega');
        Route::put('/entrega/updDetalle','VentaController@updDetalle');
        Route::get('/venta/obtenerDetallesEntrega','VentaController@obtenerDetallesEntrega');
        Route::put('/entrega/updImagen','VentaController@updImage');
        Route::put('/entrega/eliminarImg', 'VentaController@eliminarImagen');
        Route::get('/venta/ExportExcel','VentaController@listarExcel');
        Route::get('/venta/ExportExcelDet','VentaController@listarExcelDet');
        Route::post('/venta/crearDeposit', 'VentaController@crearDeposit');
        Route::get('/venta/getDeposits', 'VentaController@getDeposits');
        Route::put('/venta/eliminarDeposit','VentaController@deleteDeposit');

        Route::get('/venta/getVentasClienteProject','VentaController@getVentasClienteProject');

        Route::get('/cotizacion', 'CotizacionController@index');
        Route::post('/cotizacion/registrar', 'CotizacionController@store');
        Route::put('/cotizacion/desactivar', 'CotizacionController@desactivar');
        Route::get('/cotizacion/obtenerCabecera','CotizacionController@obtenerCabecera');
        Route::get('/cotizacion/obtenerDetalles','CotizacionController@obtenerDetalles');
        Route::get('/cotizacion/pdf/{id}','CotizacionController@pdf');
        Route::put('/cotizacion/aceptarCotizacion', 'CotizacionController@aceptarCotizacion');
        Route::get('/cotizacion/nextNum','CotizacionController@getLastNum');
        Route::post('/cotizacion/actualizarObservacion', 'CotizacionController@actualizarObservacion');
        Route::put('/cotizacion/desactivarVenta', 'CotizacionController@desactivarVenta');
        Route::post('/cotizacion/enviarCotizacionMail', 'CotizacionController@enviarCotizacionMail');

        Route::get('/tarea', 'TareaController@index');
        Route::post('/tarea/registrar', 'TareaController@store');
        Route::put('/tarea/actualizar', 'TareaController@update');
        Route::put('/tarea/desactivar', 'TareaController@desactivar');
        Route::put('/tarea/completar', 'TareaController@completar');
        Route::get('/tarea/obtenerTareas','TareaController@obtenerTareasCliente');

        Route::get('/event', 'EventController@index');
        Route::post('/event/registrar', 'EventController@store');
        Route::put('/event/actualizar', 'EventController@update');
        Route::delete('/event/{id}','EventController@destroy');
        Route::put('/event/completar', 'EventController@completar');
        Route::get('/event/obtenerEventsCliente','EventController@obtenerEventsCliente');
        Route::get('/event/listarEventos', 'EventController@listarEventos');
        Route::get('/event/ExportExcel','EventController@listarExcel');

        Route::get('/traslado', 'TrasladoController@index');
        Route::post('/traslado/registrar', 'TrasladoController@store');
        Route::put('/traslado/desactivar', 'TrasladoController@desactivar');
        Route::get('/traslado/obtenerCabecera', 'TrasladoController@obtenerCabecera');
        Route::get('/traslado/obtenerDetalles', 'TrasladoController@obtenerDetalles');
        Route::get('/traslado/nextNum','TrasladoController@getLastNum');
        Route::get('/traslado/pdf/{id}','TrasladoController@pdf');
        Route::post('/traslado/cambiarEntrega','TrasladoController@cambiarEntrega');
        Route::post('/traslado/actualizarObservacion','TrasladoController@actualizarObservacion');
        Route::put('/traslado/updImagen','TrasladoController@updImage');
        Route::put('/traslado/eliminarImg', 'TrasladoController@eliminarImagen');


        Route::get('/actividad', 'ActivityController@index');
        Route::post('/actividad/registrar', 'ActivityController@store');
        Route::put('/actividad/actualizar', 'ActivityController@update');
        Route::put('/actividad/desactivar', 'ActivityController@desactivar');
        Route::put('/actividad/cambiarEstado','ActivityController@cambiarEstado');
        Route::get('/actividad/getActivitiesUser','ActivityController@getActivitiesUser');
        Route::post('/actividad/crearComment', 'ActivityController@crearComment');
        Route::get('/actividad/getComments', 'ActivityController@getComments');
        Route::put('/actividad/editComment','ActivityController@editComment');
        Route::put('/actividad/deleteComment','ActivityController@deleteComment');
        Route::get('/actividad/getUsers', 'ActivityController@getUsers');

        Route::get('/call','CallController@index');
        Route::post('call/registrarCliente', 'CallController@storeCliente');
        Route::post('call/registrarProveedor', 'CallController@storeProveedor');
        Route::put('/call/actualizar', 'CallController@update');
        Route::put('/call/desactivar', 'CallController@desactivar');
        Route::post('/call/crearComment', 'CallController@crearComment');
        Route::get('/call/getComments', 'CallController@getComments');
        Route::put('/call/editComment','CallController@editComment');
        Route::put('/call/deleteComment','CallController@deleteComment');
        Route::put('/call/cambiarEstado','CallController@cambiarEstado');

        Route::get('/project', 'ProjectController@index');
        Route::post('/project/registrar', 'ProjectController@store');
        Route::get('/project/getSales', 'ProjectController@getVentas');

        Route::put('/project/desactivar','ProjectController@desactivar');
        Route::post('/project/cambiarEntrega','ProjectController@cambiarEntrega');
        Route::post('/project/cambiarEntregaParcial','ProjectController@cambiarEntregaParcial');
        Route::post('/project/cambiarPagado','ProjectController@cambiarPagado');
        Route::post('/project/actualizarObservacion','ProjectController@actualizarObservacion');
        Route::post('/project/actualizarObservacionPriv','ProjectController@actualizarObservacionPriv');
        Route::post('/project/crearDeposit', 'ProjectController@crearDeposit');
        Route::get('/project/getDeposits', 'ProjectController@getDeposits');
        Route::put('/project/eliminarDeposit','ProjectController@deleteDeposit');
        Route::get('/project/refreshProject', 'ProjectController@refreshProject');
        Route::put('/project/actualizar', 'ProjectController@update');
        Route::put('/project/filesupplo', 'ProjectController@filesUppload');
        Route::get('/project/getDocs', 'ProjectController@getDocs');
        Route::put('/project/eliminarDoc', 'ProjectController@eliminarDoc');
    });
});


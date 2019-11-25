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

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/nextNum','IngresoController@getLastNum');

        Route::get('/venta','VentaController@index');
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

        Route::get('/entrega','VentaController@indexEntregas');
        Route::get('/entrega/pdf/{id}','VentaController@pdfEntrega');
        Route::put('/entrega/updDetalle','VentaController@updDetalle');
        Route::get('/venta/obtenerDetallesEntrega','VentaController@obtenerDetallesEntrega');
        Route::put('/entrega/updImagen','VentaController@updImage');
        Route::put('/entrega/eliminarImg', 'VentaController@eliminarImagen');

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

        Route::get('/tarea', 'TareaController@index');
        Route::post('/tarea/registrar', 'TareaController@store');
        Route::put('/tarea/actualizar', 'TareaController@update');
        Route::put('/tarea/desactivar', 'TareaController@desactivar');
        Route::put('/tarea/completar', 'TareaController@completar');
        Route::get('/tarea/obtenerTareas','TareaController@obtenerTareasCliente');

    });
});


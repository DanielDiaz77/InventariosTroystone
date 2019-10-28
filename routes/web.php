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

/* use Illuminate\Routing\Route; */

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
        //CATEGORIAS
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        //ARTICULOS
        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::post('/articulo/registrarDetalle', 'ArticuloController@storeDetalle');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf')->name('articulos_pdf');

        //PROVEEDORES
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        //INGRESOS
        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::get('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/nextNum','IngresoController@getLastNum');

    });

    Route::group(['middleware'=>['Vendedor']],function(){
        //CLIENTES
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        //ARTICULOS
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');

        //VENTAS
        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::put('/articulo/actualizarCorte', 'ArticuloController@updateCortado');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
        Route::post('/venta/cambiarEntrega', 'VentaController@cambiarEntrega');
        Route::post('/venta/cambiarPagado', 'VentaController@cambiarPagado');
        Route::post('/venta/actualizarObservacion', 'VentaController@actualizarObservacion');
        Route::get('/venta/nextNum','VentaController@getLastNum');


    });

    Route::group(['middleware'=>['Administrador']],function(){

        //CATEGORIAS
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        //ARTICULOS
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
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf')->name('articulos_pdf');
        Route::get('/articulo/listarExcel','ArticuloController@listarExcel')->name('articulos_excel');
        Route::put('/articulo/cambiarComprometido', 'ArticuloController@cambiarComprometido');
        Route::get('/articulo/listarArticuloCotizado', 'ArticuloController@listarArticuloCotizado');

        //PROVEEDORES
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        //CLIENTES
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        //Roles
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        //USERS
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        //INGRESOS
        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/nextNum','IngresoController@getLastNum');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::put('/articulo/actualizarCorte', 'ArticuloController@updateCortado');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
        Route::post('/venta/cambiarEntrega', 'VentaController@cambiarEntrega');
        Route::post('/venta/cambiarPagado', 'VentaController@cambiarPagado');
        Route::post('/venta/actualizarObservacion', 'VentaController@actualizarObservacion');
        Route::get('/venta/nextNum','VentaController@getLastNum');

        //COTIZACIONES
        Route::get('/cotizacion', 'CotizacionController@index');
        Route::post('/cotizacion/registrar', 'CotizacionController@store');
        Route::put('/cotizacion/desactivar', 'CotizacionController@desactivar');
        Route::get('/cotizacion/obtenerCabecera','CotizacionController@obtenerCabecera');
        Route::get('/cotizacion/obtenerDetalles','CotizacionController@obtenerDetalles');
        Route::get('/cotizacion/pdf/{id}','CotizacionController@pdf')->name('cotizacion_pdf');
        Route::put('/cotizacion/aceptarCotizacion', 'CotizacionController@aceptarCotizacion');
        Route::get('/cotizacion/nextNum','CotizacionController@getLastNum');


    });
});

//Route::get('/home', 'HomeController@index')->name('home');

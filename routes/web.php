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
});

Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

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

        //PROVEEDORES
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        //INGRESOS
        Route::get('/ingreso', 'ingresoController@index');
        Route::post('/ingreso/registrar', 'ingresoController@store');
        Route::get('/ingreso/desactivar', 'ingresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'ingresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'ingresoController@obtenerDetalles');

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
        Route::get('/venta', 'ventaController@index');
        Route::post('/venta/registrar', 'ventaController@store');
        Route::put('/venta/desactivar', 'ventaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'ventaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'ventaController@obtenerDetalles');
        Route::put('/articulo/actualizarCorte', 'ArticuloController@updateCortado');


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
        Route::get('/ingreso', 'ingresoController@index');
        Route::post('/ingreso/registrar', 'ingresoController@store');
        Route::put('/ingreso/desactivar', 'ingresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'ingresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'ingresoController@obtenerDetalles');

        //VENTAS
        Route::get('/venta', 'ventaController@index');
        Route::post('/venta/registrar', 'ventaController@store');
        Route::put('/venta/desactivar', 'ventaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'ventaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'ventaController@obtenerDetalles');

    });
});

//Route::get('/home', 'HomeController@index')->name('home');

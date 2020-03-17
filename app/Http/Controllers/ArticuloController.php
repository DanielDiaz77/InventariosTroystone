<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Exports\ArticulosVentasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\ArticulosExport;
use App\Exports\ArticulosFiltrosExport;
use Illuminate\Http\Request;
use App\DetalleVenta;
use App\Categoria;
use Carbon\Carbon;
use App\Articulo;
use App\Venta;
use App\User;
use App\Link;


class ArticuloController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $usarea = \Auth::user()->area;

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $bodega = $request->bodega;
        $acabado = $request->acabado;
        $estado = $request->estado;


        if($criterio == 'idcategoria'){
            if($buscar != ''){
                $name = $request->buscar;
                $category = Categoria::where('nombre','like','%'.$name.'%')->select('id')->first();
                $buscar = $category->id;
            }
        }

        if($estado == 1){
            if($bodega == ''){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis']])->get();
                    }
                }
            }elseif($bodega =='nol'){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.stock','>',0],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();
                    }
                }
            }else{
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','>',0]])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.ubicacion',$bodega],['articulos.stock','>',0]])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','>',0]])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.ubicacion',$bodega],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0]])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0]])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0]])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','>',0],['articulos.terminado','like', '%'. $acabado . '%']])->get();
                    }
                }
            }
        }elseif($estado == 2){
            if($bodega == ''){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])->get();

                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado']])->get();


                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado']])->get();

                    }
                }
            }elseif($bodega =='nol'){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.stock','<=',0],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])->get();

                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.ubicacion','!=','San Luis'],['ventas.estado','Registrado'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();

                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.stock','<=',0],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['ventas.estado','Registrado'],['articulos.ubicacion','!=','Bodega L']])->get();

                    }
                }
            }else{
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado']])->get();

                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado'],
                            ['articulos.terminado','like', '%'. $acabado . '%']])->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado'],
                            ['articulos.terminado','like', '%'. $acabado . '%']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.ubicacion',$bodega],['articulos.stock','<=',0],['ventas.estado','Registrado'],
                            ['articulos.terminado','like', '%'. $acabado . '%']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['ventas.estado','Registrado']])->get();

                    }else{
                        $articulos = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->join('ventas','detalle_ventas.idventa','ventas.id')
                        ->leftjoin('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'ventas.num_comprobante as venta','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['ventas.estado','Registrado']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
                        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['ventas.estado','Registrado']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
                        ->leftJoin('ventas','ventas.id','detalle_ventas.idventa')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.stock','<=',0],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['ventas.estado','Registrado']])->get();
                    }
                }
            }
        }elseif($estado == 3){
            if($bodega == ''){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis']])->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis']])->get();
                    }
                }
            }elseif($bodega =='nol'){
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.condicion',3],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion','!=','San Luis'],['articulos.ubicacion','!=','Bodega L']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion','!=','Bodega L']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.terminado','like', '%'. $acabado . '%'],['articulos.ubicacion','!=','San Luis'],
                            ['articulos.ubicacion','!=','Bodega L']])->get();
                    }
                }
            }else{
                if($buscar==''){
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.condicion',3]])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.ubicacion',$bodega],['articulos.condicion',3]])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.ubicacion',$bodega],['articulos.condicion',3]])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.ubicacion',$bodega],['articulos.condicion',3],
                            ['articulos.terminado','like', '%'. $acabado . '%']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion',$bodega],['articulos.condicion',3]])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.terminado','like', '%'. $acabado . '%'],
                            ['articulos.ubicacion',$bodega],['articulos.condicion',3]])->get();
                    }
                }else{
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.condicion',3]])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.ubicacion', $bodega],['articulos.condicion',3],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.ubicacion', $bodega],['articulos.condicion',3],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']])->get();

                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.ubicacion', $bodega],
                            ['articulos.condicion',3],['articulos.terminado','like', '%'. $acabado . '%']])
                        ->orderBy('articulos.id', 'desc')->paginate(12);

                        $total = Articulo::where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion', $bodega],['articulos.terminado','like', '%'. $acabado . '%']])->count();

                        $sumaMts = DB::table('articulos')->select(DB::raw('SUM(metros_cuadrados) as metros'))
                        ->where([['articulos.'.$criterio, 'like', '%'. $buscar . '%'],['articulos.condicion',3],
                            ['articulos.ubicacion', $bodega],['articulos.terminado','like', '%'. $acabado . '%']])->get();
                    }
                }
            }
        }

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],

            'articulos' => $articulos,
            'total' => $total,
            'userarea' => $usarea,
            'sumaMts' => $sumaMts
        ];
    }
    public function buscarArticulo(Request $request){

        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $articulos = Articulo::where('codigo',$filtro)
        ->select('id','nombre','sku','codigo','origen','contenedor','ubicacion','fecha_llegada',
        'idcategoria','terminado','espedor','file','largo','alto','metros_cuadrados','precio_venta')->orderBy('sku','asc')->take(1)->get();
        return ['articulos' => $articulos];

    }
    public function buscarArticuloVenta(Request $request){

        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
        ->select('articulos.id','articulos.nombre','articulos.sku','articulos.codigo','articulos.origen',
        'articulos.contenedor','articulos.ubicacion','articulos.fecha_llegada','articulos.idcategoria',
        'articulos.terminado','articulos.espesor','articulos.largo','articulos.alto','articulos.metros_cuadrados',
        'articulos.precio_venta','articulos.stock','categorias.nombre as nombre_categoria','categorias.id as idcategoria',
        'articulos.descripcion','articulos.observacion','articulos.file','articulos.comprometido')
        ->where([
            ['codigo',$filtro],
            ['articulos.stock','>',0],
            ['articulos.condicion',1]
        ])
        ->orderBy('articulos.sku','asc')->take(1)->get();
        return ['articulos' => $articulos];

    }
    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        $fileName = "";

        if($request->file != ""){

            $exploded = explode(',', $request->file);

            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0],'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';

            $fileName = str_random().'.'.$extension;
            //The name of the directory that we need to create.
            $directoryName = 'images';

            //Check if the directory already exists.
            if(!is_dir($directoryName)){
                //Directory does not exist, so lets create it.
                mkdir($directoryName, 0777);
            }

            $path = public_path($directoryName).'/'.$fileName;

            file_put_contents($path,$decoded);
        }

        $articulo = new Articulo();
        $articulo->idcategoria      =   $request->idcategoria;
        $articulo->codigo           =   $request->codigo;
        $articulo->sku              =   $request->sku;
        $articulo->nombre           =   $request->nombre;
        $articulo->terminado        =   $request->terminado;
        $articulo->largo            =   $request->largo;
        $articulo->alto             =   $request->alto;
        $articulo->metros_cuadrados =   $request->metros_cuadrados;
        $articulo->espesor          =   $request->espesor;
        $articulo->precio_venta     =   $request->precio_venta;
        $articulo->ubicacion        =   $request->ubicacion;
        $articulo->contenedor       =   $request->contenedor;
        $articulo->stock            =   $request->stock;
        $articulo->descripcion      =   $request->descripcion;
        $articulo->observacion      =   $request->observacion;
        $articulo->origen           =   $request->origen;
        $articulo->fecha_llegada    =   $request->fecha_llegada;
        $articulo->file             =   $fileName;
        $articulo->condicion        =   '1';
        $articulo->save();
    }
    public function storeDetalle(Request $request){

        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $detalles = $request->data; //Array detalles

            //Recorrido de todos los elementos
            foreach($detalles as $ep=>$art){
                $fileName ="";
                if($art['imagen'] != ""){
                    $exploded = explode(',', $art['imagen']);

                    $decoded = base64_decode($exploded[1]);

                    if(str_contains($exploded[0],'jpeg'))
                        $extension = 'jpg';
                    else
                        $extension = 'png';

                    $fileName = str_random().'.'.$extension;

                    //The name of the directory that we need to create.
                    $directoryName = 'images';
                    if(!is_dir($directoryName)){
                        //Directory does not exist, so lets create it.
                        mkdir($directoryName, 0777);
                    }

                    $path = public_path($directoryName).'/'.$fileName;

                    file_put_contents($path,$decoded);
                }

                $articulo = new Articulo();
                $articulo->idcategoria      =   $art['idcategoria'];
                $articulo->codigo           =   $art['codigo'];
                $articulo->sku              =   $art['sku'];
                $articulo->terminado        =   $art['terminado'];
                $articulo->largo            =   $art['largo'];
                $articulo->alto             =   $art['alto'];
                $articulo->metros_cuadrados =   $art['metros_cuadrados'];
                $articulo->espesor          =   $art['espesor'];
                $articulo->precio_venta     =   $art['precio_venta'];
                $articulo->ubicacion        =   $art['ubicacion'];
                $articulo->contenedor       =   $art['contenedor'];
                $articulo->stock            =   $art['stock'];
                $articulo->descripcion      =   $art['descripcion'];
                $articulo->observacion      =   $art['observacion'];
                $articulo->origen           =   $art['origen'];
                $articulo->fecha_llegada    =   $art['fecha_llegada'];
                $articulo->file             =   $fileName;
                $articulo->condicion        =   '1';
                $articulo->save();
            }
            DB::commit();

        }catch(Exception $e){

            DB::rollBack();

        }
    }
    public function update(Request $request){

        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria      =   $request->idcategoria;
            $articulo->codigo           =   $request->codigo;
            $articulo->sku              =   $request->sku;
            $articulo->nombre           =   $request->nombre;
            $articulo->terminado        =   $request->terminado;
            $articulo->largo            =   $request->largo;
            $articulo->alto             =   $request->alto;
            $articulo->metros_cuadrados =   $request->metros_cuadrados;
            $articulo->espesor          =   $request->espesor;
            $articulo->precio_venta     =   $request->precio_venta;
            $articulo->ubicacion        =   $request->ubicacion;
            $articulo->contenedor       =   $request->contenedor;
            $articulo->stock            =   $request->stock;
            $articulo->descripcion      =   $request->descripcion;
            $articulo->observacion      =   $request->observacion;
            $articulo->origen           =   $request->origen;
            $articulo->fecha_llegada    =   $request->fecha_llegada;
            $articulo->file             =   $request->file;
            $articulo->condicion        =   '1';
            $articulo->save();

            $enlaces = $request->enlaces;
            $userid = \Auth::user()->id;

            //Recorro todos los elementos
            foreach($enlaces as $ep=>$enl){
                $link = new Link(['user_id' => $userid, 'url' => $enl['url'], 'direction' => $enl['direction']]);
                $articulo->links()->save($link);
            }

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }

        /* if($request->file != ""){

            $directoryName = 'images';

            //Check if the directory already exists.
            if(!is_dir($directoryName)){
                //Directory does not exist, so lets create it.
                mkdir($directoryName, 0777);
            }

            $art= Articulo::findOrFail($request->id);
            $img = $art->file;

            if($img != null){
                $image_path = public_path($directoryName).'/'.$img;

                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            $exploded = explode(',', $request->file);
            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0],'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';

            $fileName = str_random().'.'.$extension;
            $path = public_path($directoryName).'/'.$fileName;
            file_put_contents($path,$decoded);

            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria      =   $request->idcategoria;
            $articulo->codigo           =   $request->codigo;
            $articulo->sku              =   $request->sku;
            $articulo->nombre           =   $request->nombre;
            $articulo->terminado        =   $request->terminado;
            $articulo->largo            =   $request->largo;
            $articulo->alto             =   $request->alto;
            $articulo->metros_cuadrados =   $request->metros_cuadrados;
            $articulo->espesor          =   $request->espesor;
            $articulo->precio_venta     =   $request->precio_venta;
            $articulo->ubicacion        =   $request->ubicacion;
            $articulo->contenedor       =   $request->contenedor;
            $articulo->stock            =   $request->stock;
            $articulo->descripcion      =   $request->descripcion;
            $articulo->observacion      =   $request->observacion;
            $articulo->origen           =   $request->origen;
            $articulo->fecha_llegada    =   $request->fecha_llegada;
            $articulo->file             =   $fileName;
            $articulo->condicion        =   '1';
            $articulo->save();

        }else{

            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria      =   $request->idcategoria;
            $articulo->codigo           =   $request->codigo;
            $articulo->sku              =   $request->sku;
            $articulo->nombre           =   $request->nombre;
            $articulo->terminado        =   $request->terminado;
            $articulo->largo            =   $request->largo;
            $articulo->alto             =   $request->alto;
            $articulo->metros_cuadrados =   $request->metros_cuadrados;
            $articulo->espesor          =   $request->espesor;
            $articulo->precio_venta     =   $request->precio_venta;
            $articulo->ubicacion        =   $request->ubicacion;
            $articulo->contenedor       =   $request->contenedor;
            $articulo->stock            =   $request->stock;
            $articulo->descripcion      =   $request->descripcion;
            $articulo->observacion      =   $request->observacion;
            $articulo->origen           =   $request->origen;
            $articulo->fecha_llegada    =   $request->fecha_llegada;
            $articulo->condicion        =   '1';
            $articulo->save();
        } */
    }
    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();

    }
    public function activar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
    public function listarArticulo(Request $request){

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $bodega = $request->bodega;
        $acabado = $request->acabado;

        if($bodega == ''){
            if($buscar==''){
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1]
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }
            }else{
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0]
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }
            }
        }else{
            if($buscar==''){
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1],
                        ['articulos.ubicacion',$bodega]
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1],
                        ['articulos.ubicacion',$bodega],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }
            }else{
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0],
                        ['articulos.ubicacion',$bodega]
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0],
                        ['articulos.ubicacion',$bodega],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(12);
                }
            }
        }

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }
    public function listarArticuloVenta(Request $request){

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $bodega = $request->bodega;
        $acabado = $request->acabado;

        $area = \Auth::user()->area;

        if($area == 'GDL'){
            if($bodega == ''){
                if($buscar==''){
                    //
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.stock','>',0],
                            ['articulos.condicion',1]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.stock','>',0],
                            ['articulos.condicion',1],
                            ['articulos.terminado','like', '%'. $acabado . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }
                }else{
                    //
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['articulos.condicion',1],
                            ['articulos.stock','>',0]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['articulos.condicion',1],
                            ['articulos.stock','>',0],
                            ['articulos.terminado','like', '%'. $acabado . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }
                }
            }else{
                if($buscar==''){
                    //
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.stock','>',0],
                            ['articulos.condicion',1],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.stock','>',0],
                            ['articulos.condicion',1],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado','like', '%'. $acabado . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }
                }else{
                    //
                    if($acabado == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['articulos.condicion',1],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                            'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                            'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                            'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                            'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                            'articulos.file','articulos.comprometido','articulos.condicion')
                        ->where([
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['articulos.condicion',1],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado','like', '%'. $acabado . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(10);
                    }
                }
            }
        }elseif($area== 'SLP'){

            if($buscar==''){
                //
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1],
                        ['articulos.ubicacion','San Luis']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(10);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.stock','>',0],
                        ['articulos.condicion',1],
                        ['articulos.ubicacion','San Luis'],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(10);
                }
            }else{
                //
                if($acabado == ''){
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0],
                        ['articulos.ubicacion','San Luis']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(10);
                }else{
                    $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                    ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                        'articulos.nombre','categorias.nombre as nombre_categoria','articulos.terminado',
                        'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                        'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                        'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                        'articulos.file','articulos.comprometido','articulos.condicion')
                    ->where([
                        ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['articulos.condicion',1],
                        ['articulos.stock','>',0],
                        ['articulos.ubicacion','San Luis'],
                        ['articulos.terminado','like', '%'. $acabado . '%']
                    ])
                    ->orderBy('articulos.id', 'desc')->paginate(10);
                }
            }
        }

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],
            'articulos' => $articulos,
            'userarea' => $area
        ];
    }
    public function listarArticuloCotizado(Request $request){

        if(!$request->ajax()) return redirect('/');

        $area = \Auth::user()->area;

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($area == 'GDL'){
            if($buscar==''){
                $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                ->join('detalle_cotizaciones','articulos.id','=','detalle_cotizaciones.idarticulo')
                ->join('cotizaciones','cotizaciones.id','=','detalle_cotizaciones.idcotizacion')
                ->join('personas','personas.id','cotizaciones.idcliente')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                    'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                    'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                    'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                    'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                    'articulos.comprometido','cotizaciones.id as idcotizacion','articulos.comprometido',
                    'cotizaciones.num_comprobante as cotizacion','cotizaciones.estado as estado_cotizacion' ,
                    'personas.nombre as cliente')
                ->where([
                    ['cotizaciones.estado','Registrado'],
                    ['articulos.condicion',1]
                ])
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }else{
                $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                ->join('detalle_cotizaciones','articulos.id','=','detalle_cotizaciones.idarticulo')
                ->join('cotizaciones','cotizaciones.id','=','detalle_cotizaciones.idcotizacion')
                ->join('personas','personas.id','cotizaciones.idcliente')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                    'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                    'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                    'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                    'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                    'articulos.comprometido','cotizaciones.id as idcotizacion','articulos.comprometido',
                    'cotizaciones.num_comprobante as cotizacion','cotizaciones.estado as estado_cotizacion' ,
                    'personas.nombre as cliente')
                ->where([
                    ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                    ['articulos.condicion',1],
                    ['cotizaciones.estado','Registrado']
                ])
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }
        }elseif($area== 'SLP'){

            if($buscar==''){
                $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                ->join('detalle_cotizaciones','articulos.id','=','detalle_cotizaciones.idarticulo')
                ->join('cotizaciones','cotizaciones.id','=','detalle_cotizaciones.idcotizacion')
                ->join('personas','personas.id','cotizaciones.idcliente')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                    'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                    'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                    'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                    'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                    'articulos.comprometido','cotizaciones.id as idcotizacion','articulos.comprometido',
                    'cotizaciones.num_comprobante as cotizacion','cotizaciones.estado as estado_cotizacion' ,
                    'personas.nombre as cliente')
                ->where([
                    ['cotizaciones.estado','Registrado'],
                    ['articulos.condicion',1],
                    ['articulos.ubicacion','San Luis']
                ])
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }else{
                $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                ->join('detalle_cotizaciones','articulos.id','=','detalle_cotizaciones.idarticulo')
                ->join('cotizaciones','cotizaciones.id','=','detalle_cotizaciones.idcotizacion')
                ->join('personas','personas.id','cotizaciones.idcliente')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                    'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                    'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                    'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                    'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                    'articulos.comprometido','cotizaciones.id as idcotizacion','articulos.comprometido',
                    'cotizaciones.num_comprobante as cotizacion','cotizaciones.estado as estado_cotizacion' ,
                    'personas.nombre as cliente')
                ->where([
                    ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                    ['articulos.condicion',1],
                    ['cotizaciones.estado','Registrado'],
                    ['articulos.ubicacion','San Luis']
                ])
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }
        }
        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],
            'articulos' => $articulos,
            'userarea' => $area
        ];
    }
    public function updateCortado(Request $request){

        if(!$request->ajax()) return redirect('/');

        $newStock = $request->stock - 1;

        $articulo = Articulo::findOrFail($request->id);
        $articulo->stock            =   $newStock;
        $articulo->condicion        =   '3';
        $articulo->observacion      = $request->observacion;
        $articulo->save();

    }
    public function selectBodega(Request $request){
        if(!$request->ajax()) return redirect('/');

        $bodegas = Articulo::where('condicion',1)
        ->select('ubicacion')->groupBy('ubicacion')->get();
        return ['bodegas' => $bodegas];
    }
    public function listarExcel(Request $request){
        $bodega = $request->bodega;
        $mytime = Carbon::now('America/Mexico_City')->format('d-m-Y');
        return Excel::download(new ArticulosExport($bodega), 'inventario-'.$bodega.'-'.$mytime.'.xlsx');
    }
    public function listarExcelVenta(Request $request){
        $bodega = $request->bodega;
        $mytime = Carbon::now('America/Mexico_City')->format('d-m-Y');
        return Excel::download(new ArticulosVentasExport($bodega), 'ArticulosNoEntregados-'.$bodega.'-'.$mytime.'.xlsx');
    }
    public function cambiarComprometido(Request $request){

        if (!$request->ajax()) return redirect('/');

        $articulo = Articulo::findOrFail($request->id);
        $articulo->comprometido = $request->comprometido;
        $articulo->idusuario = \Auth::user()->id;
        $articulo->save();
    }
    public function eliminarImagen(Request $request){
        if(!$request->ajax()) return redirect('/');

        $directoryName = 'images';
        //Check if the directory already exists.
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0777);
        }

        $art= Articulo::findOrFail($request->id);
        $img = $art->file;

        if($img != null){
            $image_path = public_path($directoryName).'/'.$img;
            if(file_exists($image_path)){
                unlink($image_path);
                $fileName = null;
            }
        }

        $articulo = Articulo::findOrFail($request->id);
        $articulo->file = $fileName;
        $articulo->save();
    }
    public function getCodesSku(Request $request){
        if(!$request->ajax()) return redirect('/');
        $codigos = Articulo::select('codigo')->get();

        return ['codigos' => $codigos];
    }
    public function listByCategory(Request $request){

        if(!$request->ajax()) return redirect('/');

        $category_id = $request->id;

        $buscar = $request->buscar;

        if($buscar != ''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.sku')
            ->addSelect(DB::raw('COUNT(articulos.sku) as total'))
            ->where([['categorias.id',$category_id],['articulos.sku','like', '%'. $buscar . '%']])
            ->groupBy('articulos.sku')
            ->paginate(12);
        }else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.sku')
            ->addSelect(DB::raw('COUNT(articulos.sku) as total'))
            ->where('categorias.id',$category_id)
            ->groupBy('articulos.sku')
            ->paginate(12);
        }

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],

            'articulos' => $articulos
        ];


    }
    public function listBySku(Request $request){

        if(!$request->ajax()) return redirect('/');

        $sku = $request->sku;

        $estado = $request->estado;
        $bodega = $request->bodega;
        $acabado = $request->acabado;

        $buscar = $request->buscar;
        $criterio = $request->criterio;



        if($estado == 1){
            if($bodega == ''){
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([['articulos.sku',$sku],['articulos.stock','>',0]])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.terminado',$acabado]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.terminado',$acabado],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado',$acabado]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','>',0],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado',$acabado],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }
        }elseif($estado == 2){
            if($bodega == ''){
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.terminado',$acabado],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.terminado',$acabado],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.stock','<=',0],
                            ['articulos.condicion','!=',3],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }
        }elseif($estado == 3){
            if($bodega == ''){
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.terminado',$acabado]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.terminado',$acabado],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($acabado == ''){
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.ubicacion',$bodega]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }else{
                    if($buscar == ''){
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado',$acabado]
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }else{
                        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
                        ->leftjoin('users','articulos.idusuario','=','users.id')
                        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                            'articulos.comprometido','users.usuario')
                        ->where([
                            ['articulos.sku',$sku],
                            ['articulos.condicion',3],
                            ['articulos.ubicacion',$bodega],
                            ['articulos.terminado',$acabado],
                            ['articulos.'.$criterio, 'like', '%'. $buscar . '%']
                        ])
                        ->orderBy('articulos.id', 'desc')->paginate(12);
                    }
                }
            }
        }

        /* BASE */
        /* $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
        ->leftjoin('users','articulos.idusuario','=','users.id')
        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
            'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
            'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
            'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
            'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
            'articulos.comprometido','users.usuario')
        ->where([['articulos.sku',$sku]])
        ->orderBy('articulos.id', 'desc')->paginate(12); */

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],

            'articulos' => $articulos
        ];
    }
    public function listarExcelFiltros(Request $request){

        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $bodega = $request->bodega;
        $acabado = $request->acabado;
        $zona = $request->zona;

        $mytime = Carbon::now('America/Mexico_City')->format('d-m-Y');

        if($buscar != null){
            if($acabado != null){
                $name = $buscar.'-'.$acabado.'-'.$mytime.'.xlsx';
            }else{
                $name = $buscar.'-'.$mytime.'.xlsx';
            }
        }else {
            $name = 'articulos'.'-'.$mytime.'.xlsx';
        }


        /* $name = $buscar.'-'.$acabado.'-'.$mytime.'.xlsx'; */

        return Excel::download(new ArticulosFiltrosExport($criterio,$buscar,$acabado,$bodega,$zona), $name);

        /* return [
            'criterio' => $criterio,
            'buscar' => $buscar,
            'bodega' => $bodega,
            'acabado' => $acabado,
            'zona' => $zona

        ]; */
    }
    public function getLinks(Request $request){

        if (!$request->ajax()) return redirect('/');

        $articulo = Articulo::findOrFail($request->id);

        $links = $articulo->links()
        ->join('users','users.id','links.user_id')
        ->leftjoin('personas', 'users.id','=','personas.id')
        ->select('links.id','links.user_id as user','links.url','links.updated_at as fecha',
            'links.direction','personas.nombre')
        ->orderBy('links.updated_at','desc')
        ->get();

        return ['links' => $links];

    }
    public function deleteLink(Request $request){
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $link = Link::findOrFail($request->id);
            $link->delete();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
}

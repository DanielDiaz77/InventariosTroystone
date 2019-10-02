<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Articulo;

class ArticuloController extends Controller
{

    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select(
                'articulos.id',
                'articulos.idcategoria',
                'articulos.codigo',
                'articulos.sku',
                'articulos.nombre',
                'categorias.nombre as nombre_categoria',
                'articulos.terminado',
                'articulos.largo',
                'articulos.alto',
                'articulos.metros_cuadrados',
                'articulos.espesor',
                'articulos.precio_venta',
                'articulos.ubicacion',
                'articulos.contenedor',
                'articulos.stock',
                'articulos.descripcion',
                'articulos.observacion',
                'articulos.origen',
                'articulos.fecha_llegada',
                'articulos.file',
                'articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(12);
        }else{

            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select(
                'articulos.id',
                'articulos.idcategoria',
                'articulos.codigo',
                'articulos.sku',
                'articulos.nombre',
                'categorias.nombre as nombre_categoria',
                'articulos.terminado',
                'articulos.largo',
                'articulos.alto',
                'articulos.metros_cuadrados',
                'articulos.espesor',
                'articulos.precio_venta',
                'articulos.ubicacion',
                'articulos.contenedor',
                'articulos.stock',
                'articulos.descripcion',
                'articulos.observacion',
                'articulos.origen',
                'articulos.fecha_llegada',
                'articulos.file',
                'articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(12);
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

    public function buscarArticulo(Request $request){

        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $articulos = Articulo::where('codigo',$filtro)
        ->select('id','nombre')->orderBy('nombre','asc')->take(1)->get();

        return ['articulos' => $articulos];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /*  return $request->all(); */
        if(!$request->ajax()) return redirect('/');

        $exploded = explode(',', $request->file);

        $decoded = base64_decode($exploded[1]);

        if(str_contains($exploded[0],'jpeg'))
            $extension = 'jpg';
        else
            $extension = 'png';

        $fileName = str_random().'.'.$extension;

        $path = public_path().'/'.$fileName;

        file_put_contents($path,$decoded);

        $articulo = new Articulo();
        $articulo->idcategoria      =   $request->idcategoria;
        $articulo->codigo           =   $request->codigo;
        $articulo->sku              =   $request->sku;
        $articulo->nombre           =   $request->nombre;
        $articulo->terminado        =   $request->terminado;
        $articulo->largo            =   $request->largo;
        $articulo->alto            =   $request->alto;
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

    public function update(Request $request){

        if(!$request->ajax()) return redirect('/');

        if($request->file != ""){

            $art= Articulo::findOrFail($request->id);
            $img = $art->file;

            if(file_exists($img)){
                $image_path = public_path().'/'.$img;
                unlink($image_path);
            }

            $exploded = explode(',', $request->file);
            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0],'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';

            $fileName = str_random().'.'.$extension;
            $path = public_path().'/'.$fileName;
            file_put_contents($path,$decoded);

            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria      =   $request->idcategoria;
            $articulo->codigo           =   $request->codigo;
            $articulo->sku              =   $request->sku;
            $articulo->nombre           =   $request->nombre;
            $articulo->terminado        =   $request->terminado;
            $articulo->largo            =   $request->largo;
            $articulo->alto            =   $request->alto;
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
            $articulo->alto            =   $request->alto;
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
        }


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

        if($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select(
                'articulos.id',
                'articulos.idcategoria',
                'articulos.codigo',
                'articulos.sku',
                'articulos.nombre',
                'categorias.nombre as nombre_categoria',
                'articulos.terminado',
                'articulos.largo',
                'articulos.alto',
                'articulos.metros_cuadrados',
                'articulos.espesor',
                'articulos.precio_venta',
                'articulos.ubicacion',
                'articulos.contenedor',
                'articulos.stock',
                'articulos.descripcion',
                'articulos.observacion',
                'articulos.origen',
                'articulos.fecha_llegada',
                'articulos.file',
                'articulos.condicion')
               /*  ->where('articulos.condicion',1) */
                ->orderBy('articulos.id', 'desc')->paginate(10);
        }else{

            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select(
                'articulos.id',
                'articulos.idcategoria',
                'articulos.codigo',
                'articulos.sku',
                'articulos.nombre',
                'categorias.nombre as nombre_categoria',
                'articulos.terminado',
                'articulos.largo',
                'articulos.alto',
                'articulos.metros_cuadrados',
                'articulos.espesor',
                'articulos.precio_venta',
                'articulos.ubicacion',
                'articulos.contenedor',
                'articulos.stock',
                'articulos.descripcion',
                'articulos.observacion',
                'articulos.origen',
                'articulos.fecha_llegada',
                'articulos.file',
                'articulos.condicion')
            ->where([
                ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                /* ['articulos.condicion',1] */
            ])
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        return ['articulos' => $articulos];
    }
}

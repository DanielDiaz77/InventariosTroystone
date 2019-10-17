<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Articulo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArticulosExport;

class ArticuloController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->leftjoin('users','articulos.idusuario','=','users.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku','articulos.nombre',
                'categorias.nombre as nombre_categoria','articulos.terminado','articulos.largo','articulos.alto',
                'articulos.metros_cuadrados','articulos.espesor','articulos.precio_venta','articulos.ubicacion',
                'articulos.contenedor','articulos.stock','articulos.descripcion','articulos.observacion',
                'articulos.origen','articulos.fecha_llegada','articulos.file','articulos.condicion',
                'articulos.comprometido','users.usuario')

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

        if($request->file != ""){

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
    public function listarArticuloVenta(Request $request){

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
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
                ['articulos.'.$criterio, 'like', '%'. $buscar . '%'],
                ['articulos.condicion',1],
                ['articulos.stock','>',0]
            ])
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        return ['articulos' => $articulos];
    }

    public function listarArticuloCotizado(Request $request){

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

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
                ['cotizaciones.estado','!=','Anulada'],
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
                ['cotizaciones.estado','!=','Anulada']
            ])
            ->orderBy('articulos.id', 'desc')->paginate(12);
        }
        return ['articulos' => $articulos];
    }

    public function updateCortado(Request $request){

        if(!$request->ajax()) return redirect('/');

        $newStock = $request->stock - 1;

        $articulo = Articulo::findOrFail($request->id);
        $articulo->stock            =   $newStock;
        $articulo->condicion        =   '3';
        $articulo->save();

    }
    /* public function listarPdf(){

        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select(
                'articulos.id','articulos.idcategoria','articulos.codigo','articulos.sku',
                'articulos.nombre','categorias.nombre as categoria','articulos.terminado',
                'articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.espesor',
                'articulos.precio_venta','articulos.ubicacion','articulos.contenedor','articulos.stock',
                'articulos.descripcion','articulos.observacion','articulos.origen','articulos.fecha_llegada',
                'articulos.file','articulos.condicion')
            ->orderBy('articulos.codigo', 'asc')->get();

        $cont = Articulo::count();

        $pdf = \PDF::loadView('pdf.articulospdf',['articulos' => $articulos,'cont'=>$cont]);

        return $pdf->download('articulos.pdf');

    } */
    public function listarExcel(){
        return Excel::download(new ArticulosExport, 'lista-articulos.xlsx');
    }
    public function cambiarComprometido(Request $request){

        if (!$request->ajax()) return redirect('/');

        $articulo = Articulo::findOrFail($request->id);
        $articulo->comprometido = $request->comprometido;
        $articulo->idusuario = \Auth::user()->id;
        $articulo->save();
    }
}

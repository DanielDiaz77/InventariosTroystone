<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traslado;
use App\DetalleTraslado;
use App\Articulo;
use App\User;

class TrasladoController extends Controller
{
    public function index(Request $request){

        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estado = $request->estado;

        if($estado == ''){
            if ($buscar==''){
                $traslados = Traslado::join('users','traslados.idusuario','=','users.id')
                ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
                'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
                'traslados.file','users.usuario')
                ->where('traslados.estado','Registrado')
                ->orderBy('traslados.id', 'desc')->paginate(12);
            }
            else{
                $traslados = Traslado::join('users','traslados.idusuario','=','users.id')
                ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
                'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
                'traslados.file','users.usuario')
                ->where([['traslados.'.$criterio, 'like', '%'. $buscar . '%'],['traslados.estado','Registrado']])
                ->orderBy('traslados.id', 'desc')->paginate(12);
            }
        }else{
            if ($buscar==''){
                $traslados = Traslado::join('users','traslados.idusuario','=','users.id')
                ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
                'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
                'traslados.file','users.usuario')
                ->where('traslados.estado',$estado)
                ->orderBy('traslados.id', 'desc')->paginate(12);
            }
            else{
                $traslados = Traslado::join('users','traslados.idusuario','=','users.id')
                ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
                'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
                'traslados.file','users.usuario')
                ->where([['traslados.'.$criterio, 'like', '%'. $buscar . '%'],['traslados.estado',$estado]])
                ->orderBy('traslados.id', 'desc')->paginate(12);
            }

        }


        return [
            'pagination' => [
                'total'        => $traslados->total(),
                'current_page' => $traslados->currentPage(),
                'per_page'     => $traslados->perPage(),
                'last_page'    => $traslados->lastPage(),
                'from'         => $traslados->firstItem(),
                'to'           => $traslados->lastItem(),
            ],
            'traslados' => $traslados
        ];
    }

    public function getLastNum(){
        $lastNum = Traslado::select('num_comprobante')->get()->last();
        $noComp = explode('"',$lastNum);
        $SigNum = explode("-",$noComp[3]);
        return ['SigNum' => $SigNum[2]];
    }

    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        try{
            DB::beginTransaction();

            $fileName =null;

            if($request->file != ""){

                //The name of the directory that we need to create.
                $directoryName = 'images/traslados';

                if(!is_dir($directoryName)){
                    //Directory does not exist, so lets create it.
                    mkdir($directoryName, 0777);
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
            }

            $traslado = new Traslado();
            $traslado->idusuario = \Auth::user()->id;
            $traslado->tipo_comprobante = $request->tipo_comprobante;
            $traslado->num_comprobante = $request->num_comprobante;
            $traslado->fecha_hora = $mytime;
            $traslado->nueva_ubicacion = $request->nueva_ubicacion;
            $traslado->estado = 'Registrado';
            $traslado->entregado = 0;
            $traslado->observacion = $request->observacion;
            $traslado->file = $fileName;
            $traslado->save();

            $detalles = $request->data;//Array de detalles

            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleTraslado();
                $detalle->idtraslado = $traslado->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->ubicacion = $det['ubicacion'];
                $detalle->save();

                $articulo = Articulo::findOrFail($det['idarticulo']);
                $articulo->ubicacion = $traslado->nueva_ubicacion;
                $articulo->save();
            }

            DB::commit();

        }catch(Exception $e){

            DB::rollBack();

        }
    }

    public function desactivar(Request $request){

        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $traslado = Traslado::findOrFail($request->id);
            $traslado->estado = 'Anulado';
            $traslado->entregado = 0;
            $traslado->save();

            $detalles = DetalleTraslado::select('idarticulo','ubicacion')
                ->where('idtraslado',$request->id)->get();

            foreach($detalles as $ep=>$det){

                $articulo = Articulo::findOrFail($det['idarticulo']);
                $articulo->ubicacion = $det['ubicacion'];
                $articulo->save();
            }

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }

    public function obtenerCabecera(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id = $request->id;

        $traslado = Traslado::join('users','traslados.idusuario','=','users.id')
        ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
            'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
            'traslados.file','users.usuario','traslados.observacion as obstraslado')
        ->where('traslados.id',$id)
        ->orderBy('traslados.id', 'desc')->take(1)->get();

        return ['traslado' => $traslado];
    }

    public function obtenerDetalles(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id =  $request->id;

        $detalles = DetalleTraslado::join('articulos','detalle_traslados.idarticulo','=','articulos.id')
        ->leftJoin('categorias','articulos.idcategoria','=','categorias.id')
        ->select('detalle_traslados.cantidad','detalle_traslados.ubicacion as ubicacionAntes','detalle_traslados.id',
            'articulos.sku','articulos.codigo','articulos.espesor','articulos.largo',
            'articulos.alto','articulos.metros_cuadrados','articulos.descripcion','articulos.idcategoria',
            'articulos.terminado','articulos.ubicacion','articulos.file','articulos.origen',
            'categorias.nombre as categoria',
            'articulos.contenedor','articulos.fecha_llegada','articulos.observacion','articulos.condicion')
        ->where('detalle_traslados.idtraslado',$id)
        ->orderBy('detalle_traslados.id','desc')->get();

        return ['detalles' => $detalles];
    }

    public function pdf(Request $request,$id){

        $traslado = Traslado::join('users','traslados.idusuario','=','users.id')
        ->select('traslados.id','traslados.tipo_comprobante','traslados.num_comprobante',
            'traslados.fecha_hora','traslados.nueva_ubicacion','traslados.entregado','traslados.estado',
            'traslados.file','traslados.observacion as comentario','users.usuario')
        ->where('traslados.id',$id)->take(1)->get();

        $detalles = DetalleTraslado::join('articulos','detalle_traslados.idarticulo','=','articulos.id')
            ->leftJoin('categorias','articulos.idcategoria','=','categorias.id')
            ->select('detalle_traslados.cantidad','detalle_traslados.ubicacion','articulos.sku as articulo',
            'articulos.largo','articulos.alto','articulos.metros_cuadrados', 'categorias.nombre as categoria',
            'articulos.codigo','articulos.ubicacion as artubicacion')
            ->where('detalle_traslados.idtraslado',$id)
            ->orderBy('detalle_traslados.id','desc')->get();

        $numtraslado = Traslado::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.traslado',['traslado' => $traslado,'detalles'=>$detalles]);

        return $pdf->stream('traslado-'.$numtraslado[0]->num_comprobante.'.pdf');

    }

    public function cambiarEntrega(Request $request){
        if (!$request->ajax()) return redirect('/');
        $traslado = Traslado::findOrFail($request->id);
        $traslado->entregado = $request->entregado;
        $traslado->save();
    }

    public function actualizarObservacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $traslado = Traslado::findOrFail($request->id);
        $traslado->observacion = $request->observacion;
        $traslado->save();
    }

    public function updImage(Request $request){

        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $fileName =null;

            if($request->file != ""){

                //The name of the directory that we need to create.
                $directoryName = 'images/traslados';

                if(!is_dir($directoryName)){
                    //Directory does not exist, so lets create it.
                    mkdir($directoryName, 0777);
                }

                $trasl = Traslado::findOrFail($request->id);
                $img = $trasl->file;

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
            }

            $traslado = Traslado::findOrFail($request->id);
            $traslado->file = $fileName;
            $traslado->save();

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }

    }

    public function eliminarImagen(Request $request){

        if(!$request->ajax()) return redirect('/');

        $directoryName = 'images/traslados';
        //Check if the directory already exists.
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0777);
        }

        $trasl= Traslado::findOrFail($request->id);
        $img = $trasl->file;

        if($img != null){
            $image_path = public_path($directoryName).'/'.$img;
            if(file_exists($image_path)){
                unlink($image_path);
                $fileName = null;
            }
        }else {
            return;
        }

        $traslado = Traslado::findOrFail($request->id);
        $traslado->file = $fileName;
        $traslado->save();

    }


}

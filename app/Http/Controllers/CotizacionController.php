<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Cotizacion;
use App\DetalleCotizacion;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;

class CotizacionController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $cotizaciones = Cotizacion::join('personas','cotizaciones.idcliente','=','personas.id')
            ->join('users','cotizaciones.idusuario','=','users.id')
            ->select('cotizaciones.id','cotizaciones.tipo_comprobante','cotizaciones.num_comprobante',
            'cotizaciones.fecha_hora','cotizaciones.vigencia','cotizaciones.impuesto','cotizaciones.total',
            'cotizaciones.estado','cotizaciones.moneda','cotizaciones.tipo_cambio','cotizaciones.observacion',
            'cotizaciones.forma_pago','cotizaciones.tiempo_entrega','cotizaciones.lugar_entrega',
            'cotizaciones.aceptado','personas.nombre','users.usuario')
            ->orderBy('cotizaciones.id', 'desc')->paginate(12);
        }
        else{
            $cotizaciones = Cotizacion::join('personas','cotizaciones.idcliente','=','personas.id')
            ->join('users','cotizaciones.idusuario','=','users.id')
            ->select('cotizaciones.id','cotizaciones.tipo_comprobante','cotizaciones.num_comprobante',
            'cotizaciones.fecha_hora','cotizaciones.vigencia','cotizaciones.impuesto','cotizaciones.total',
            'cotizaciones.estado','cotizaciones.moneda','cotizaciones.tipo_cambio','cotizaciones.observacion',
            'cotizaciones.forma_pago','cotizaciones.tiempo_entrega','cotizaciones.lugar_entrega',
            'cotizaciones.aceptado','personas.nombre','users.usuario')
            ->where('cotizaciones.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('cotizaciones.id', 'desc')->paginate(12);
        }


        return [
            'pagination' => [
                'total'        => $cotizaciones->total(),
                'current_page' => $cotizaciones->currentPage(),
                'per_page'     => $cotizaciones->perPage(),
                'last_page'    => $cotizaciones->lastPage(),
                'from'         => $cotizaciones->firstItem(),
                'to'           => $cotizaciones->lastItem(),
            ],
            'cotizaciones' => $cotizaciones
        ];
    }

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        try{
            DB::beginTransaction();

            $cotizacion = new Cotizacion();
            $cotizacion->idcliente = $request->idcliente;
            $cotizacion->idusuario = \Auth::user()->id;
            $cotizacion->tipo_comprobante = $request->tipo_comprobante;
            $cotizacion->num_comprobante = $request->num_comprobante;
            $cotizacion->fecha_hora = $mytime;
            $cotizacion->vigencia = $request->vigencia;
            $cotizacion->impuesto = $request->impuesto;
            $cotizacion->total = $request->total;
            $cotizacion->forma_pago = $request->forma_pago;
            $cotizacion->tiempo_entrega = $request->tiempo_entrega;
            $cotizacion->lugar_entrega = $request->lugar_entrega;
            $cotizacion->aceptado = 0;
            $cotizacion->estado = 'Registrado';
            $cotizacion->moneda = $request->moneda;
            $cotizacion->tipo_cambio = $request->tipo_cambio;
            $cotizacion->observacion = $request->observacion;

            $cotizacion->save();

            $detalles = $request->data;//Array de detalles

            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleCotizacion();
                $detalle->idcotizacion = $cotizacion->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
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

            $cotizacion = Cotizacion::findOrFail($request->id);
            $cotizacion->estado = 'Anulada';
            $cotizacion->aceptado = 0;
            $cotizacion->save();

            $detalles = DetalleCotizacion::select('idarticulo')
                    ->where('idcotizacion',$request->id)->get();

            foreach($detalles as $ep=>$det){
                $articulo = Articulo::findOrFail($det['idarticulo']);
                $articulo->comprometido = 0;
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
        $cotizacion = Cotizacion::join('personas','cotizaciones.idcliente','=','personas.id')
        ->join('users','cotizaciones.idusuario','=','users.id')
        ->select('cotizaciones.id','cotizaciones.tipo_comprobante','cotizaciones.num_comprobante',
        'cotizaciones.fecha_hora','cotizaciones.impuesto','cotizaciones.total','cotizaciones.estado',
        'cotizaciones.moneda','cotizaciones.tipo_cambio','cotizaciones.observacion','cotizaciones.forma_pago',
        'cotizaciones.tiempo_entrega','cotizaciones.lugar_entrega','cotizaciones.aceptado','cotizaciones.vigencia',
        'personas.nombre','users.usuario')
        ->where('cotizaciones.id','=',$id)
        ->orderBy('cotizaciones.id', 'desc')->take(1)->get();

        return ['cotizacion' => $cotizacion];
    }

    public function obtenerDetalles(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id =  $request->id;

        $detalles = DetalleCotizacion::join('articulos','detalle_cotizaciones.idarticulo','=','articulos.id')
        ->select('detalle_cotizaciones.cantidad','detalle_cotizaciones.precio','detalle_cotizaciones.descuento',
            'articulos.sku','articulos.codigo','articulos.espesor','articulos.largo','articulos.alto',
            'articulos.metros_cuadrados','articulos.descripcion','articulos.idcategoria','articulos.terminado',
            'articulos.ubicacion','articulos.file','articulos.origen','articulos.contenedor','articulos.fecha_llegada',
            'articulos.observacion','articulos.condicion')
        ->where('detalle_cotizaciones.idcotizacion',$id)
        ->orderBy('detalle_cotizaciones.id','desc')->get();

        return ['detalles' => $detalles];
    }

    public function pdf(Request $request,$id){

        $cotizacion =  Cotizacion::join('personas','cotizaciones.idcliente','=','personas.id')
        ->join('users','cotizaciones.idusuario','=','users.id')
        ->select('cotizaciones.id','cotizaciones.tipo_comprobante','cotizaciones.num_comprobante',
            'cotizaciones.created_at','cotizaciones.impuesto','cotizaciones.total','cotizaciones.estado',
            'cotizaciones.forma_pago','cotizaciones.tiempo_entrega','cotizaciones.lugar_entrega',
            'cotizaciones.aceptado','cotizaciones.moneda','cotizaciones.tipo_cambio', 'cotizaciones.observacion',
            'cotizaciones.vigencia','personas.nombre','personas.rfc','personas.domicilio','personas.ciudad',
            'personas.telefono','personas.email','users.usuario')
        ->where('cotizaciones.id',$id)->take(1)->get();

        $detalles = DetalleCotizacion::join('articulos','detalle_cotizaciones.idarticulo','=','articulos.id')
            ->select('detalle_cotizaciones.cantidad','detalle_cotizaciones.precio','detalle_cotizaciones.descuento',
                'articulos.sku as articulo','articulos.largo','articulos.alto','articulos.metros_cuadrados')
            ->where('detalle_cotizaciones.idcotizacion',$id)
            ->orderBy('detalle_cotizaciones.id','desc')->get();

        $numcotizacion = Cotizacion::select('num_comprobante')->where('id',$id)->get();

        $ivaagregado = Cotizacion::select('impuesto')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.cotizacion',['cotizacion' => $cotizacion,'detalles'=>$detalles,'ivaCotizacion' =>$ivaagregado[0]->impuesto]);

        return $pdf->stream('cotizacion-'.$numcotizacion[0]->num_comprobante.'.pdf');
    }

    public function aceptarCotizacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $cotizacion = Cotizacion::findOrFail($request->id);
        $cotizacion->aceptado = $request->aceptado;
        $cotizacion->save();
    }

    public function getLastNum(){

        $lastNum = Cotizacion::select('num_comprobante')->get()->last();
        $noComp = explode('"',$lastNum);
        $SigNum = explode("-",$noComp[3]);
        return ['SigNum' => $SigNum[2]];
    }

    public function actualizarObservacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $cotizacion = Cotizacion::findOrFail($request->id);
        $cotizacion->observacion = $request->observacion;
        $cotizacion->save();
    }
}

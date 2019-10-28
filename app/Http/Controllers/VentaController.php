<?php

namespace App\Http\Controllers;

use App\Venta;
use App\DetalleVenta;
use App\User;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.num_cheque',
            'ventas.banco','ventas.tipo_facturacion','ventas.pagado','personas.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(12);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.num_cheque',
            'ventas.banco','ventas.tipo_facturacion','ventas.pagado','personas.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.id', 'desc')->paginate(12);
        }


        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }
    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        try{
            DB::beginTransaction();

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime;
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->forma_pago = $request->forma_pago;
            $venta->tiempo_entrega = $request->tiempo_entrega;
            $venta->lugar_entrega = $request->lugar_entrega;
            $venta->entregado = 0;
            $venta->estado = 'Registrado';
            $venta->moneda = $request->moneda;
            $venta->tipo_cambio = $request->tipo_cambio;
            $venta->observacion = $request->observacion;
            $venta->num_cheque = $request->num_cheque;
            $venta->banco = $request->banco;
            $venta->tipo_facturacion = $request->tipo_facturacion;

            $venta->save();

            $detalles = $request->data;//Array de detalles

            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }

            $fechaActual= date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count();

            $arregloDatos = [
            'ventas' => [
                        'numero' => $numVentas,
                        'msj' => 'Ventas'
                    ],
            'ingresos' => [
                        'numero' => $numIngresos,
                        'msj' => 'Ingresos'
                    ]
            ];
            $allUsers = User::all();

            foreach ($allUsers as $notificar) {
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }

            DB::commit();

        }catch(Exception $e){

            DB::rollBack();

        }
    }

    public function desactivar(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulada';
        $venta->entregado = 0;
        $venta->pagado = 0;
        $venta->save();
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.num_cheque',
        'ventas.banco','ventas.tipo_facturacion','ventas.pagado','personas.nombre','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id =  $request->id;

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.sku','articulos.codigo',
            'articulos.espesor','articulos.largo','articulos.alto','articulos.metros_cuadrados','articulos.descripcion',
            'articulos.idcategoria','articulos.terminado','articulos.ubicacion','articulos.file','articulos.origen',
            'articulos.contenedor','articulos.fecha_llegada','articulos.observacion','articulos.condicion')
        ->where('detalle_ventas.idventa',$id)
        ->orderBy('detalle_ventas.id','desc')->get();

        return ['detalles' => $detalles];
    }

    public function pdf(Request $request,$id){

        $venta =  Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.created_at','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.forma_pago','ventas.tiempo_entrega','ventas.lugar_entrega',
            'ventas.entregado','ventas.moneda','ventas.tipo_cambio', 'ventas.observacion',
            'ventas.num_cheque','ventas.banco','ventas.tipo_facturacion','ventas.pagado',
            'personas.nombre','personas.rfc','personas.domicilio','personas.ciudad',
            'personas.telefono','personas.email','users.usuario')
        ->where('ventas.id',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'articulos.sku as articulo','articulos.largo','articulos.alto','articulos.metros_cuadrados')
            ->where('detalle_ventas.idventa',$id)
            ->orderBy('detalle_ventas.id','desc')->get();

        $numventa = Venta::select('num_comprobante')->where('id',$id)->get();

        $ivaagregado = Venta::select('impuesto')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta',['venta' => $venta,'detalles'=>$detalles,'ivaVenta' =>$ivaagregado[0]->impuesto]);

        return $pdf->stream('venta-'.$numventa[0]->num_comprobante.'.pdf');
    }

    public function cambiarEntrega(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->entregado = $request->entregado;
        $venta->save();
    }

    public function cambiarPagado(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->pagado = $request->pagado;
        $venta->save();
    }

    public function actualizarObservacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->observacion = $request->observacion;
        $venta->save();
    }

    public function getLastNum(){
        $lastNum = Venta::select('num_comprobante')->get()->last();
        $noComp = explode('"',$lastNum);
        $SigNum = explode("-",$noComp[3]);
        return ['SigNum' => $SigNum[2]];
    }
}

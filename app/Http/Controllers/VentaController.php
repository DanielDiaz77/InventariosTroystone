<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Venta;
use App\DetalleVenta;
use App\User;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;

class VentaController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($criterio == 'entregado'){
            $buscar = 1;
        }

        if($criterio == 'entrega_parcial'){
            $buscar = 1;
        }


        if ($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
            'ventas.tipo_facturacion','users.usuario','observacionpriv')
            ->orderBy('ventas.id', 'desc')->paginate(12);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
            'ventas.tipo_facturacion','users.usuario','observacionpriv')
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
            $venta->entrega_parcial = 0;
            $venta->estado = 'Registrado';
            $venta->moneda = $request->moneda;
            $venta->tipo_cambio = $request->tipo_cambio;
            $venta->observacion = $request->observacion;
            $venta->observacionpriv = $request->observacionpriv;
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
                $detalle->por_entregar = $det['cantidad'];
                $detalle->entregadas = 0;
                $detalle->pendientes = $det['cantidad'];
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
        try{
            DB::beginTransaction();

            $venta = Venta::findOrFail($request->id);
            $venta->estado = 'Anulada';
            $venta->entregado = 0;
            $venta->entrega_parcial = 0;
            $venta->pagado = 0;
            $venta->save();

            $detalles = DetalleVenta::select('idarticulo','cantidad')
                ->where('idventa',$request->id)->get();

            foreach($detalles as $ep=>$det){

                $articulo = Articulo::findOrFail($det['idarticulo']);
                $articulo->stock += $det['cantidad'];
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
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
        'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
        'ventas.num_cheque','personas.nombre','ventas.file','ventas.observacionpriv')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id =  $request->id;

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->leftJoin('categorias','articulos.idcategoria','=','categorias.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
            'detalle_ventas.por_entregar','detalle_ventas.pendientes','detalle_ventas.entregadas',
            'detalle_ventas.id','articulos.sku','articulos.codigo','articulos.espesor','articulos.largo',
            'articulos.alto','articulos.metros_cuadrados','articulos.descripcion','articulos.idcategoria',
            'articulos.terminado','articulos.ubicacion','articulos.file','articulos.origen',
            'categorias.nombre as categoria',
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
            'personas.telefono','personas.email','users.usuario','ventas.entrega_parcial',
            'ventas.observacionpriv')
        ->where('ventas.id',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'articulos.sku as articulo','articulos.largo','articulos.alto',
                'articulos.metros_cuadrados','articulos.codigo')
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
        $venta->entrega_parcial = 0;
        $venta->save();
    }

    public function cambiarEntregaParcial(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->entrega_parcial = $request->entrega_parcial;
        $venta->entregado = 0;
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

    public function actualizarObservacionPriv(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->observacionpriv = $request->observacionpriv;
        $venta->save();
    }

    public function getLastNum(){
        $lastNum = Venta::select('num_comprobante')->get()->last();
        $noComp = explode('"',$lastNum);
        $SigNum = explode("-",$noComp[3]);
        return ['SigNum' => $SigNum[2]];
    }

    public function indexEntregas(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($criterio == 'entregado'){
            $buscar = 1;
        }

        if($criterio == 'entrega_parcial'){
            $buscar = 1;
        }

        if ($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
            'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
            'ventas.entrega_parcial','users.usuario','ventas.observacionpriv')
            ->where([
                ['ventas.pagado',1],
                ['ventas.estado','!=','Anulada']
            ])
            ->orderBy('ventas.id', 'desc')->paginate(12);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado',
            'ventas.num_cheque','ventas.entrega_parcial','ventas.banco','users.usuario',
            'ventas.tipo_facturacion','ventas.pagado','personas.nombre','ventas.observacionpriv')
            ->where([
                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                ['ventas.pagado',1],
                ['ventas.estado','!=','Anulada']
            ])
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

    public function obtenerDetallesEntrega(Request $request){

        if (!$request->ajax()) return redirect('/');

        $id =  $request->id;

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->leftJoin('categorias','articulos.idcategoria','=','categorias.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
            'detalle_ventas.por_entregar','detalle_ventas.pendientes','detalle_ventas.entregadas',
            'detalle_ventas.id','articulos.sku','articulos.codigo','articulos.espesor','articulos.largo',
            'articulos.alto','articulos.metros_cuadrados','articulos.descripcion','articulos.idcategoria',
            'articulos.terminado','articulos.ubicacion','articulos.file','articulos.origen','categorias.nombre as categoria',
            'articulos.contenedor','articulos.fecha_llegada','articulos.observacion','articulos.condicion')
        ->where([
            ['detalle_ventas.idventa',$id],
            ['detalle_ventas.completado',0]
        ])
        ->orderBy('detalle_ventas.id','desc')->get();

        return ['detalles' => $detalles];
    }

    public function pdfEntrega(Request $request,$id){

        $venta =  Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.created_at','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.forma_pago','ventas.tiempo_entrega','ventas.lugar_entrega',
            'ventas.entregado','ventas.moneda','ventas.tipo_cambio', 'ventas.observacion',
            'ventas.num_cheque','ventas.banco','ventas.tipo_facturacion','ventas.pagado',
            'personas.nombre','personas.rfc','personas.domicilio','personas.ciudad',
            'personas.telefono','personas.email','users.usuario','ventas.entrega_parcial')
        ->where('ventas.id',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'detalle_ventas.entregadas','detalle_ventas.pendientes','articulos.sku as articulo',
                'articulos.largo','articulos.alto','articulos.metros_cuadrados', 'articulos.codigo')
            ->where('detalle_ventas.idventa',$id)
            ->orderBy('detalle_ventas.id','desc')->get();

        $numventa = Venta::select('num_comprobante')->where('id',$id)->get();

        $ivaagregado = Venta::select('impuesto')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.entrega',['venta' => $venta,'detalles'=>$detalles,'ivaVenta' =>$ivaagregado[0]->impuesto]);

        return $pdf->stream('entrega-'.$numventa[0]->num_comprobante.'.pdf');
    }

    public function updDetalle(Request $request){
        try{
            DB::beginTransaction();

            $detalles = $request->data;//Array de detalles

            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $Stcomplete = 0;

                if($det['entregadas'] == $det['cantidad']){
                    $Stcomplete = 1;
                }

                $detalle = DetalleVenta::findOrFail($det['id']);
                $detalle->entregadas = $det['entregadas'];
                $detalle->pendientes = $det['pendientes']-$det['entregadas'];
                $detalle->completado = $Stcomplete;
                $detalle->save();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }

    public function updImage(Request $request){

        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $fileName ="";

            if($request->file != ""){

                //The name of the directory that we need to create.
                $directoryName = 'entregas';

                if(!is_dir($directoryName)){
                    //Directory does not exist, so lets create it.
                    mkdir($directoryName, 0777);
                }

                $vent= Venta::findOrFail($request->id);
                $img = $vent->file;

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

            $venta = Venta::findOrFail($request->id);
            $venta->file = $fileName;
            $venta->save();

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

    }

    public function obtenerVentasCliente(Request $request){

        //if (!$request->ajax()) return redirect('/');
        $idcliente = $request->idcliente;

        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
        'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
        'ventas.num_cheque','personas.nombre','ventas.file','ventas.observacionpriv')
        ->where('ventas.idcliente','=',$idcliente)
        ->orderBy('ventas.fecha_hora','desc')->paginate(5);

        return ['ventas' => $ventas];
    }
}

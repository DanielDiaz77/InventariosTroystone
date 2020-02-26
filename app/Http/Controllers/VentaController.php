<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Venta;
use App\DetalleVenta;
use App\User;
use App\Deposit;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VentasExport;
use App\Exports\VentasExportDet;



class VentaController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estadoV = $request->estado;
        $entregaEs = $request->estadoEntrega;
        $usrol = \Auth::user()->idrol;
        $usid = \Auth::user()->id;
        $usarea = \Auth::user()->area;

        if($usarea != 'SLP'){
            if($estadoV == "Anulada"){
                if($criterio == "cliente"){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where([
                        ['personas.nombre', 'like', '%'. $buscar . '%'],
                        ['ventas.estado',$estadoV]
                    ])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == "user"){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado',$estadoV]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    if ($buscar==''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where('ventas.estado',$estadoV)
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
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['ventas.estado',$estadoV]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }

            }else{
                if($buscar==''){
                    if($entregaEs == 'entregado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($entregaEs == 'entrega_parcial'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($entregaEs == 'no_entregado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }else{
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado']])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }
                else{
                    if($criterio == "cliente"){
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }elseif($criterio == "user"){
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entregado',1]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entregado',0],['ventas.entrega_parcial',0]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado']])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }else{
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }
                }
            }
        }else{
            if($estadoV == "Anulada"){
                if($criterio == "cliente"){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where([['personas.nombre', 'like', '%'. $buscar . '%'],['ventas.estado',$estadoV],
                        ['ventas.idusuario',$usid]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == "user"){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado',$estadoV],
                        ['ventas.idusuario',$usid]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    if ($buscar==''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado',$estadoV],['ventas.idusuario',$usid]])
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
                        'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                            ['ventas.estado',$estadoV],
                            ['ventas.idusuario',$usid]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }

            }else{
                if($buscar==''){
                    if($entregaEs == 'entregado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1],
                            ['ventas.idusuario',$usid]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($entregaEs == 'entrega_parcial'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1],
                            ['ventas.idusuario',$usid]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($entregaEs == 'no_entregado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0],
                            ['ventas.idusuario',$usid]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }else{
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado'],['ventas.idusuario',$usid]])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }
                else{
                    if($criterio == "cliente"){
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }elseif($criterio == "user"){
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entregado',1],['ventas.idusuario',$usid]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],['ventas.idusuario',$usid]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.entregado',0],['ventas.entrega_parcial',0],['ventas.idusuario',$usid]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([['users.usuario', 'like', '%'. $buscar . '%'],['ventas.estado','Registrado'],
                                ['ventas.idusuario',$usid]])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }else{
                        if($entregaEs == 'entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'entrega_parcial'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($entregaEs == 'no_entregado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }else{
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.idusuario',$usid]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }
                }
            }
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
            'ventas' => $ventas,
            'userrol' => $usrol
        ];
    }
    public function indexInvo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estadoV = $request->estado;
        $tipoFact = $request->tipofact;

        $usrol = \Auth::user()->idrol;
        $usarea = \Auth::user()->area;
        $usid = \Auth::user()->id;

        if($usarea == 'SLP'){
            if ($buscar==''){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','personas.rfc as rfccliente','ventas.adeudo',
                    'ventas.num_factura')
                ->where([['ventas.adeudo',0],['ventas.facturado',$estadoV],
                    ['ventas.tipo_facturacion',$tipoFact],['ventas.estado','Registrado'],
                    ['ventas.idusuario',$usid]])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }elseif($criterio == 'cliente'){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.adeudo','ventas.num_factura')
                ->where([['ventas.adeudo',0],['personas.nombre', 'like', '%'. $buscar . '%'],
                    ['ventas.facturado',$estadoV],['ventas.tipo_facturacion',$tipoFact],
                    ['ventas.estado','Registrado'],['ventas.idusuario',$usid]])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.adeudo','ventas.num_factura')
                ->where([['ventas.adeudo',0],['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                    ['ventas.facturado',$estadoV],['ventas.tipo_facturacion',$tipoFact],
                    ['ventas.estado','Registrado'],['ventas.idusuario',$usid]])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }
        }else{
            if($buscar==''){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','personas.rfc as rfccliente','ventas.adeudo',
                    'ventas.num_factura')
                ->where([['ventas.adeudo',0],['ventas.facturado',$estadoV],
                    ['ventas.tipo_facturacion',$tipoFact],['ventas.estado','Registrado']])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }elseif($criterio == 'cliente'){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.adeudo','ventas.num_factura')
                ->where([['ventas.adeudo',0],['personas.nombre', 'like', '%'. $buscar . '%'],
                    ['ventas.facturado',$estadoV],['ventas.tipo_facturacion',$tipoFact],
                    ['ventas.estado','Registrado']])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.adeudo','ventas.num_factura')
                ->where([['ventas.adeudo',0],['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                    ['ventas.facturado',$estadoV],['ventas.tipo_facturacion',$tipoFact],
                    ['ventas.estado','Registrado']
                ])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }
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
            'ventas' => $ventas,
            'userrol' => $usrol
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
            $venta->adeudo = $request->total;
            $venta->forma_pago = $request->forma_pago;
            $venta->tiempo_entrega = $request->tiempo_entrega;
            $venta->lugar_entrega = $request->lugar_entrega;
            $venta->entregado = 0;
            $venta->entrega_parcial = 0;
            $venta->pago_parcial = 0;
            $venta->estado = 'Registrado';
            $venta->moneda = $request->moneda;
            $venta->tipo_cambio = $request->tipo_cambio;
            $venta->observacion = $request->observacion;
            $venta->observacionpriv = $request->observacionpriv;
            $venta->num_cheque = $request->num_cheque;
            $venta->banco = $request->banco;
            $venta->tipo_facturacion = $request->tipo_facturacion;
            $venta->facturado = 0;
            $venta->factura_env = 0;
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
            $venta->pago_parcial = 0;
            $venta->facturado = 0;
            $venta->factura_env = 0;
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
            'ventas.num_cheque','ventas.file','ventas.observacionpriv','ventas.facturado',
            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo','personas.nombre as cliente',
            'personas.tipo','personas.rfc','personas.cfdi','personas.telefono',
            'personas.company as contacto','personas.tel_company as tel_contacto')
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
            'personas.company as contacto','personas.tel_company',
            'ventas.observacionpriv','ventas.facturado','ventas.factura_env',
            'ventas.pago_parcial','ventas.adeudo')
        ->where('ventas.id',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'articulos.sku as articulo','articulos.largo','articulos.alto','articulos.terminado',
                'articulos.metros_cuadrados','articulos.codigo','articulos.ubicacion')
            ->where('detalle_ventas.idventa',$id)
            ->orderBy('detalle_ventas.id','desc')->get();

        $numventa = Venta::select('num_comprobante')->where('id',$id)->get();

        $ivaagregado = Venta::select('impuesto')->where('id',$id)->get();

        $sumaMts = DB::table('articulos')
        ->select(DB::raw('SUM(metros_cuadrados) as metros'))
        ->leftJoin('detalle_ventas','detalle_ventas.idarticulo','articulos.id')
        ->where('detalle_ventas.idventa',$id)
        ->get();


        $pdf = \PDF::loadView('pdf.venta',['venta' => $venta,'detalles'=>$detalles,'ivaVenta' =>$ivaagregado[0]->impuesto,'sumaMts' => $sumaMts[0]->metros]);

        return $pdf->stream('venta-'.$numventa[0]->num_comprobante.'.pdf');
    }
    public function cambiarEntrega(Request $request){

        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $venta = Venta::findOrFail($request->id);
            $venta->entregado = $request->entregado;
            $venta->entrega_parcial = 0;
            $venta->save();

            $detalles = DetalleVenta::where('idventa',$venta->id)->get();

            if($request->entregado == 1){
                foreach($detalles as $ep=>$det){
                    $detail = DetalleVenta::findOrFail($det['id']);
                    $detail->entregadas = $det['pendientes'];
                    $detail->pendientes = $det['pendientes']-$det['por_entregar'];
                    $detail->completado = 1;
                    $detail->save();
                }
            }else{
                foreach($detalles as $ep=>$det){
                    $detail = DetalleVenta::findOrFail($det['id']);
                    $detail->entregadas = 0;
                    $detail->pendientes = $det['por_entregar'];
                    $detail->completado = 0;
                    $detail->save();
                }
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        /* return ['detalles' => $detalles]; */

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

        /* $pag_par = 0; */

        if($request->pagado == 1){
            $venta = Venta::findOrFail($request->id);
            $venta->pagado = $request->pagado;
            $venta->adeudo = 0;
            $venta->save();
        }else{
            $venta = Venta::findOrFail($request->id);
            $venta->pagado = $request->pagado;
            $venta->adeudo = $venta->total;
            $venta->save();
        }


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
        $entregaEs = $request->estadoEntrega;
        $usrol = \Auth::user()->idrol;


        if($usrol != 1){
            if($entregaEs == ''){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],
                        ['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'entregado'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entregado',1]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entregado',1],
                        ['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entregado',1],
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'entrega_parcial'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1],
                        ['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1],
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'no_entregado'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0],['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.pagado',1],['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0],['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }
        }else{
            if($entregaEs == ''){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'entregado'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entregado',1]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entregado',1],
                        ['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entregado',1],
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'entrega_parcial'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1],
                        ['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',1],
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }elseif($entregaEs == 'no_entregado'){
                if($buscar == ''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0]])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0],['personas.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                        'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                        'ventas.num_cheque','ventas.tipo_facturacion','ventas.pagado','personas.nombre',
                        'ventas.entrega_parcial','users.usuario','ventas.observacionpriv','ventas.facturado',
                        'ventas.factura_env','ventas.adeudo')
                    ->where([['ventas.estado','!=','Anulada'],['ventas.entrega_parcial',0],
                        ['ventas.entregado',0],['ventas.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }
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
            'personas.telefono','personas.email','users.usuario','ventas.entrega_parcial',
            'personas.company as contacto','personas.tel_company','ventas.facturado','ventas.factura_env')
        ->where('ventas.id',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'detalle_ventas.entregadas','detalle_ventas.pendientes','articulos.sku as articulo',
                'articulos.largo','articulos.alto','articulos.metros_cuadrados', 'articulos.codigo',
                'articulos.ubicacion')
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

            $venta = Venta::findOrFail($request->idventa);

            if($request->totales == 0){
                $venta->entrega_parcial = 0;
                $venta->entregado = 1;
                $venta->save();
            }else{
                $venta->entrega_parcial = 1;
                $venta->entregado = 0;
                $venta->save();
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

        if (!$request->ajax()) return redirect('/');
        $idcliente = $request->idcliente;

        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
            'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
            'ventas.num_cheque','personas.nombre','ventas.file','ventas.observacionpriv',
            'ventas.facturado','ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
        ->where('ventas.idcliente','=',$idcliente)
        ->orderBy('ventas.fecha_hora','desc')->paginate(5);

        return ['ventas' => $ventas];
    }
    public function eliminarImagen(Request $request){
        if(!$request->ajax()) return redirect('/');

        $directoryName = 'entregas';
        //Check if the directory already exists.
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0777);
        }

        $art= Venta::findOrFail($request->id);
        $img = $art->file;

        if($img != null){
            $image_path = public_path($directoryName).'/'.$img;
            if(file_exists($image_path)){
                unlink($image_path);
                $fileName = null;
            }
        }
        $venta = Venta::findOrFail($request->id);
        $venta->file = $fileName;
        $venta->save();

    }
    public function ListarExcel(Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;

        return Excel::download(new VentasExport($inicio,$fin), 'presupuestos-'.$inicio.'-'.$fin.'.xlsx');
    }
    public function ListarExcelDet(Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        return Excel::download(new VentasExportDet($inicio,$fin), 'DetallePresupuestos-'.$inicio.'-'.$fin.'.xlsx');
    }
    public function cambiarFacturacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        if($request->estado == 0){
            $venta->facturado = 0;
            $venta->factura_env = 0;
            $venta->num_factura = null;
        }else{
            $venta->facturado = $request->estado;
            $venta->num_factura = $request->numFact;
            $venta->factura_env = 0;
        }
        $venta->save();
    }
    public function cambiarFacturacionEnv(Request $request){
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->factura_env = $request->estadoEn;
        $venta->save();
    }
    public function crearDeposit(Request $request){

        if (!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        $venta = Venta::findOrFail($request->id); //Venta a depositar
        $adeudoAct = $venta->adeudo;

        if($request->total < $adeudoAct){
            try{
                DB::beginTransaction();
                $venta->adeudo = $venta->adeudo - $request->total;
                $venta->pago_parcial = 1;
                $venta->pagado = 0;
                $venta->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime]);
                $venta->deposits()->save($deposit);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }elseif($request->total == $adeudoAct){
            try{
                DB::beginTransaction();
                $venta->adeudo = 0;
                $venta->pago_parcial = 1;
                $venta->pagado = 1;
                $venta->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime]);
                $venta->deposits()->save($deposit);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }
    }
    public function getDeposits(Request $request){

        if (!$request->ajax()) return redirect('/');

        $venta = Venta::findOrFail($request->id); //ID venta y sus depositos

        $deposits = $venta->deposits()
        ->select('deposits.id','deposits.total','deposits.fecha_hora as fecha')
        ->orderBy('deposits.fecha_hora','desc')
        ->get();

        /* $tot = $venta->deposits()->count(); */

        return [
            'abonos' => $deposits,
            /* 'total'  => $tot */
        ];

    }
    public function deleteDeposit(Request $request){
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $deposit = Deposit::findOrFail($request->id);
            $deposit->delete();

            /*  $venta = Venta::findOrFail($request->idventa); */
            $venta = Venta::findOrFail($request->idventa);
            $numDeposits = $venta->deposits()->count();

            if($numDeposits <= 0){
                $venta->pago_parcial = 0;
                $venta->pagado = 0;
                $venta->adeudo = $venta->total;
                $venta->save();
            }else{
                $venta->adeudo = $venta->adeudo + $request->total;
                $venta->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function indexDeposit(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estadoV = $request->estado;
        $entregaEs = $request->estadoEntrega;
        $usrol = \Auth::user()->idrol;
        $usid = \Auth::user()->id;
        $estadoAdeu = $request->estadoAdeudo;

        if($estadoV == "Anulada"){
            if($criterio == "cliente"){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                ->where([
                    ['personas.nombre', 'like', '%'. $buscar . '%'],
                    ['ventas.estado',$estadoV]
                ])
                ->orderBy('ventas.id', 'desc')->paginate(12);
            }else{
                if ($buscar==''){
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where('ventas.estado',$estadoV)
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }else{
                    $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                    'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                    'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                    'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                    'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                    ->where([
                        ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['ventas.estado',$estadoV]
                    ])
                    ->orderBy('ventas.id', 'desc')->paginate(12);
                }
            }
        }else{
            if($buscar==''){
                if($entregaEs == 'entregado'){
                    if($estadoAdeu == ''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Pagado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1],
                            ['ventas.pagado',1],['ventas.adeudo',0]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Abonado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1],
                            ['ventas.pago_parcial',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'NoAbono'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',1],
                            ['ventas.adeudo','=','ventas.total']
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }elseif($entregaEs == 'entrega_parcial'){
                    if($estadoAdeu == ''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Pagado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1],
                            ['ventas.pagado',1],['ventas.adeudo',0]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Abonado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1],
                            ['ventas.pago_parcial',1]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'NoAbono'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entrega_parcial',1],
                            ['ventas.adeudo','=','ventas.total']
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }elseif($entregaEs == 'no_entregado'){
                    if($estadoAdeu == ''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Pagado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0],
                            ['ventas.pagado',1],['ventas.adeudo',0]
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Abonado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0],
                            ['ventas.pago_parcial',1]

                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'NoAbono'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([
                            ['ventas.estado','Registrado'],
                            ['ventas.entregado',0],
                            ['ventas.entrega_parcial',0],
                            ['ventas.adeudo','=','ventas.total']
                        ])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }else{
                    if($estadoAdeu == ''){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado']])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Pagado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado'],['ventas.pagado',1],['ventas.adeudo',0]])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'Abonado'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado'],['ventas.pago_parcial',1]])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }elseif($estadoAdeu == 'NoAbono'){
                        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                        ->where([['ventas.estado','Registrado'],['ventas.total','ventas.adeudo']])
                        ->orderBy('ventas.id', 'desc')->paginate(12);
                    }
                }
            }
            else{
                if($criterio == "cliente"){
                    if($entregaEs == 'entregado'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }

                    }elseif($entregaEs == 'entrega_parcial'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);

                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }elseif($entregaEs == 'no_entregado'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }else{
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                            'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                            'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                            'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['personas.nombre', 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }
                }else{
                    if($entregaEs == 'entregado'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',1],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }elseif($entregaEs == 'entrega_parcial'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entrega_parcial',1],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }elseif($entregaEs == 'no_entregado'){
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.entregado',0],
                                ['ventas.entrega_parcial',0],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }else{
                        if($estadoAdeu == ''){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Pagado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.pagado',1],['ventas.adeudo',0]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'Abonado'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.pago_parcial',1]
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }elseif($estadoAdeu == 'NoAbono'){
                            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                            ->join('users','ventas.idusuario','=','users.id')
                            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                                'ventas.entrega_parcial','ventas.num_cheque','ventas.pagado','personas.nombre',
                                'ventas.tipo_facturacion','users.usuario','observacionpriv','ventas.facturado',
                                'ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
                            ->where([
                                ['ventas.'.$criterio, 'like', '%'. $buscar . '%'],
                                ['ventas.estado','Registrado'],
                                ['ventas.adeudo','=','ventas.total']
                            ])
                            ->orderBy('ventas.id', 'desc')->paginate(12);
                        }
                    }
                }
            }
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
            'ventas' => $ventas,
            'userrol' => $usrol
        ];
    }
    public function getVentasClienteProject(Request $request){

        if (!$request->ajax()) return redirect('/');

        $idcliente = $request->idcliente;
        $buscar = $request->buscar;
        $criterio = $request->criterio;


        if($buscar != ''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
                'ventas.num_cheque','personas.nombre','ventas.file','ventas.observacionpriv',
                'ventas.facturado','ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
            ->where([
                ['ventas.estado','Registrado'],
                ['ventas.'.$criterio, 'like', '%'. $buscar . '%']
            ])
            ->orderBy('ventas.fecha_hora','desc')->paginate(10);
        }else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante',
                'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
                'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
                'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
                'ventas.num_cheque','personas.nombre','ventas.file','ventas.observacionpriv',
                'ventas.facturado','ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
            ->where([
                    ['ventas.idcliente','=',$idcliente],
                    ['ventas.estado','Registrado']
                ])
            ->orderBy('ventas.fecha_hora','desc')->paginate(10);
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
}

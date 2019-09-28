<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Ingreso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.num_comprobante',
            'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
            'personas.nombre','users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(12);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.num_comprobante',
            'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
            'personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ingresos.id', 'desc')->paginate(12);
        }


        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        try{
            DB::beginTransaction();

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $request->$mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';

            $ingreso->save();

            $detalles = $request->data; //Array detalles

            //Recorrido de todos los elementos

            foreach($detalles as $ep=>$det){

                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio_compra = $det['precio_compra'];
                $detalle->save();
            }

            DB::commit();

        }catch(Exception $e){

            DB::rollBack();

        }
    }

    public function desactivar(Request $request){

        if(!$request->ajax()) return redirect('/');
        $ingreso = Ingresos::findOrFail($request->id);
        $ingreso->estado = 'Cancelado';
        $ingreso->save();

    }
}

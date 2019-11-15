<?php

namespace App\Http\Controllers;

use App\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $tareas = Tarea::join('personas','personas.id','=','tareas.idcliente')
            ->join('users','users.id','=','tareas.idusuario')
            ->select('personas.id','personas.nombre as cliente','personas.domicilio','personas.telefono',
            'personas.ciudad','personas.rfc','personas.email','users.usuario',
            'users.idrol','users.area','tareas.id','tareas.nombre','tareas.descripcion',
            'tareas.fecha','tareas.estado')
            ->orderBy('personas.id', 'desc')->paginate(12);

        return [
            'pagination' => [
                'total'        => $tareas->total(),
                'current_page' => $tareas->currentPage(),
                'per_page'     => $tareas->perPage(),
                'last_page'    => $tareas->lastPage(),
                'from'         => $tareas->firstItem(),
                'to'           => $tareas->lastItem(),
            ],
            'tareas' => $tareas
        ];
    }
    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $tarea = new Tarea();
            $tarea->nombre = $request->nombre;
            $tarea->descripcion = $request->descripcion;
            $tarea->tipo = $request->tipo;
            $tarea->fecha = $request->fecha;
            $tarea->estado        =   '0';
            $tarea->idusuario = \Auth::user()->id;
            $tarea->idcliente = $request->idcliente;
            $tarea->save();
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $tarea = new Tarea();
            $tarea->nombre = $request->nombre;
            $tarea->descripcion = $request->descripcion;
            $tarea->tipo = $request->tipo;
            $tarea->fecha = $request->fecha;
            $tarea->estado        =   '0';
            $tarea->idusuario = \Auth::user()->id;
            $tarea->idcliente = $request->idcliente;
            $tarea->save();

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $tarea = Tarea::findOrFail($request->id);
        $tarea->estado = '2';
        $tarea->save();

    }
    public function completar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $tarea = Tarea::findOrFail($request->id);
        $tarea->estado = '1';
        $tarea->save();
    }
}

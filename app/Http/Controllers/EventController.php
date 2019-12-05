<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Event;
use App\User;
use App\Persona;

class EventController extends Controller
{
    public function index(Request $request){

        if(!$request->ajax()) return redirect('/');

        $usrol = \Auth::user()->idrol;

        if($usrol == 2){
            $usvend = \Auth::user()->id;
            $eventos = Event::join('users','users.id','=','events.idusuario')
            ->join('personas','personas.id','=','events.idcliente')
            ->select('users.id as userid','users.usuario as user','events.id','events.start',
            'events.end','events.title','events.content','events.title','events.class','personas.rfc',
            'events.estado','personas.id as idcliente','personas.nombre as cliente','personas.domicilio',
            'personas.telefono','personas.ciudad','personas.observacion','personas.email','personas.tipo',
            'personas.company','personas.tel_company')
            ->where('events.idusuario',$usvend)
            ->orderby('events.id')
            ->get();

        }else{
            $eventos = Event::join('users','users.id','=','events.idusuario')
            ->join('personas','personas.id','=','events.idcliente')
            ->select('users.id as userid','users.usuario as user','events.id','events.start',
            'events.end','events.title','events.content','events.title','events.class','personas.rfc',
            'events.estado','personas.id as idcliente','personas.nombre as cliente','personas.domicilio',
            'personas.telefono','personas.ciudad','personas.observacion','personas.email','personas.tipo',
            'personas.company','personas.tel_company')
            ->orderby('events.id')
            ->get();
        }

        return ['eventos' => $eventos,'userrol' => $usrol];
    }
    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        $event = new Event();
        $event->start = $request->start;
        $event->end = $request->end;
        $event->title = $request->title;
        $event->content = $request->content;
        $event->class = $request->clase;
        $event->idusuario = \Auth::user()->id;
        $event->idcliente = $request->idcliente;
        $event->estado = '0';
        $event->save();
    }
    public function update(Request $request){

        if(!$request->ajax()) return redirect('/');

        $event = Event::findOrFail($request->id);
        $event->start = $request->start;
        $event->end = $request->end;
        $event->title = $request->title;
        $event->content = $request->content;
        $event->class = $request->clase;
        $event->idusuario = $request->idusuario;
        $event->idcliente = $request->idcliente;
        $event->estado = $request->estado;
        $event->save();

    }
    public function destroy(Request $request){
        if(!$request->ajax()) return redirect('/');
        $event = Event::findOrFail($request->id);
        $event->delete();
    }
    public function completar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $event = Event::findOrFail($request->id);
        $event->estado = $request->estado;
        $event->save();
    }
    public function obtenerEventsCliente(Request $request){

        if (!$request->ajax()) return redirect('/');

        $idcliente = $request->idcliente;

        $eventos = Event::join('users','users.id','=','events.idusuario')
        ->join('personas','personas.id','=','events.idcliente')
        ->select('users.usuario as user','events.id','events.start',
        'events.end','events.title','events.content','events.title','events.class',
        'events.estado')
        ->where([
            ['events.idcliente','=',$idcliente],
            ['events.estado',0]
        ])
        ->orderBy('events.start','desc')->paginate(1);

        return [
            'pagination' => [
                'total'         => $eventos->total(),
                'current_page'  => $eventos->currentPage(),
                'per_page'      => $eventos->perPage(),
                'last_page'     => $eventos->lastPage(),
                'from'          => $eventos->firstItem(),
                'to'            => $eventos->lastItem(),
            ],
            'actividades' => $eventos
        ];
    }
}

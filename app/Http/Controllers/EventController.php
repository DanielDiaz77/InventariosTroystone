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

        $eventos = Event::join('users','users.id','=','events.idusuario')
        ->join('personas','personas.id','=','events.idcliente')
        ->select('users.id as userid','users.usuario as user','events.id','events.start',
        'events.end','events.title','events.content','events.title','events.class','events.estado',
        'personas.id as idcliente','personas.nombre as cliente','personas.domicilio','personas.telefono',
        'personas.ciudad','personas.rfc','personas.email','personas.tipo','personas.company','personas.tel_company')
        ->orderby('events.start')
        ->get();

        return ['eventos' => $eventos];
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
        $event->save();

    }
    public function destroy(Request $request){
        if(!$request->ajax()) return redirect('/');
        $event = Event::findOrFail($request->id);
        $event->delete();
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\ActividadesExport;

use Carbon\Carbon;
use App\Event;
use App\User;
use App\Persona;

class EventController extends Controller
{
    public function index(Request $request){

        if(!$request->ajax()) return redirect('/');

        $zona = $request->zona;

        $usrol = \Auth::user()->idrol;
        $usarea = \Auth::user()->area;

        if($usrol == 1){
            if($zona == ''){
                $eventos = Event::join('users','users.id','=','events.idusuario')
                ->join('personas','personas.id','=','events.idcliente')
                ->select('users.id as userid','users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class','personas.rfc',
                    'events.estado','personas.id as idcliente','personas.nombre as cliente','personas.domicilio',
                    'personas.telefono','personas.ciudad','personas.observacion','personas.email','personas.tipo',
                    'personas.company','personas.tel_company','events.area')
                ->orderby('events.id')
                ->get();
            }else{
                $eventos = Event::join('users','users.id','=','events.idusuario')
                ->join('personas','personas.id','=','events.idcliente')
                ->select('users.id as userid','users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class','personas.rfc',
                    'events.estado','personas.id as idcliente','personas.nombre as cliente','personas.domicilio',
                    'personas.telefono','personas.ciudad','personas.observacion','personas.email','personas.tipo',
                    'personas.company','personas.tel_company','events.area')
                ->where('events.area',$zona)
                ->orderby('events.id')
                ->get();
            }

        }else{
            $eventos = Event::join('users','users.id','=','events.idusuario')
            ->join('personas','personas.id','=','events.idcliente')
            ->select('users.id as userid','users.usuario as user','events.id','events.start',
                'events.end','events.title','events.content','events.title','events.class','personas.rfc',
                'events.estado','personas.id as idcliente','personas.nombre as cliente','personas.domicilio',
                'personas.telefono','personas.ciudad','personas.observacion','personas.email','personas.tipo',
                'personas.company','personas.tel_company','events.area')
            ->where('events.area',$usarea)
            ->orderby('events.id')
            ->get();
        }

        return ['eventos' => $eventos,'userrol' => $usrol,'userarea' => $usarea];
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
        $event->area = $request->area;
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
        $event->area = $request->area;
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
        ->where('events.idcliente',$idcliente)
        /* ->where([
            ['events.idcliente','=',$idcliente],
            ['events.estado',0]
        ]) */
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
    public function listarEventos(Request $request){

        //if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $estado = $request->estado;
        $area = $request->area;

        if($area != ''){
            if($estado != ''){
                if($buscar!=''){
                    if($criterio == 'cliente'){
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([
                            ['events.area',$area],
                            ['events.estado',$estado],
                            ['personas.nombre', 'like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);
                    }else{
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([
                            ['events.area',$area],
                            ['events.estado',$estado],
                            ['events.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);
                    }
                }else{
                    $eventos = Event::join('users','users.id','=','events.idusuario')
                    ->join('personas','personas.id','=','events.idcliente')
                    ->select('users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class',
                    'events.estado','events.area','personas.nombre as cliente')
                    ->where([['events.area',$area],['events.estado',$estado]])
                    ->orderBy('events.start','desc')->paginate(12);
                }
            }else{
                if($buscar!=''){
                    if($criterio=='cliente'){
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([
                            ['events.area',$area],
                            ['personas.nombre','like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);

                    }else{
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([
                            ['events.area',$area],
                            ['events.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);
                    }
                }else{
                    $eventos = Event::join('users','users.id','=','events.idusuario')
                    ->join('personas','personas.id','=','events.idcliente')
                    ->select('users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class',
                    'events.estado','events.area','personas.nombre as cliente')
                    ->where('events.area',$area)
                    ->orderBy('events.start','desc')->paginate(12);
                }
            }
        }else{
            if($estado != ''){
                if($buscar!=''){
                    if($criterio == 'cliente'){
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([
                            ['events.estado',$estado],
                            ['personas.nombre', 'like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);
                    }else{
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where([['events.estado',$estado],['events.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('events.start','desc')->paginate(12);
                    }
                }else{
                    $eventos = Event::join('users','users.id','=','events.idusuario')
                    ->join('personas','personas.id','=','events.idcliente')
                    ->select('users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class',
                    'events.estado','events.area','personas.nombre as cliente')
                    ->where('events.estado',$estado)
                    ->orderBy('events.start','desc')->paginate(12);
                }
            }else{
                if($buscar!=''){
                    if($criterio == 'cliente'){
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where('personas.nombre', 'like', '%'. $buscar . '%')
                        ->orderBy('events.start','desc')->paginate(12);
                    }else{
                        $eventos = Event::join('users','users.id','=','events.idusuario')
                        ->join('personas','personas.id','=','events.idcliente')
                        ->select('users.usuario as user','events.id','events.start',
                        'events.end','events.title','events.content','events.title','events.class',
                        'events.estado','events.area','personas.nombre as cliente')
                        ->where('events.'.$criterio, 'like', '%'. $buscar . '%')
                        ->orderBy('events.start','desc')->paginate(12);
                    }
                }else{
                    $eventos = Event::join('users','users.id','=','events.idusuario')
                    ->join('personas','personas.id','=','events.idcliente')
                    ->select('users.usuario as user','events.id','events.start',
                    'events.end','events.title','events.content','events.title','events.class',
                    'events.estado','events.area','personas.nombre as cliente')
                    ->orderBy('events.start','desc')->paginate(12);
                }
            }
        }

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
    public function ListarExcel(Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        return Excel::download(new ActividadesExport($inicio,$fin), 'actividades-'.$inicio.'-'.$fin.'.xlsx');
    }
}

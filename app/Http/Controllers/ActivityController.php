<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Activity;
use App\User;


class ActivityController extends Controller
{
    public function index(Request $request){

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estado = $request->estado;

        $usrol = \Auth::user()->idrol;
        $user = \Auth::user()->id;

        if($usrol != 1){
            if($buscar != ''){
                if($criterio == 'receptor'){
                    $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([
                        ['activities.idreceptor',$user],
                        ['activities.status',$estado],
                        ['personaRec.nombre', 'like', '%'. $buscar . '%']
                    ])
                    ->orderBy('activities.start','desc')->paginate(12);
                }elseif($criterio == 'emisor'){
                    $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([
                        ['activities.idreceptor',$user],
                        ['activities.status',$estado],
                        ['personaEmi.nombre', 'like', '%'. $buscar . '%']
                    ])
                    ->orderBy('activities.start','desc')->paginate(12);
                }else{
                  $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([
                        ['activities.idreceptor',$user],
                        ['activities.status',$estado],
                        ['activities.'.$criterio, 'like', '%'. $buscar . '%']
                    ])
                    ->orderBy('activities.start','desc')->paginate(12);
                }
            }else{
                $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                ->join('users AS emisor','emisor.id','=','activities.idemisor')
                ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                ->select('activities.id','activities.start','activities.end',
                    'activities.title','activities.content','activities.status',
                    'activities.idreceptor','activities.idemisor',
                    'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                ->where([['activities.idreceptor',$user],['activities.status',$estado]])
                ->orderBy('activities.start','desc')->paginate(12);
            }

        }else{
            if($buscar != ''){
                if($criterio == 'receptor'){
                    //['personas.nombre', 'like', '%'. $buscar . '%']
                    $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([
                        ['activities.status',$estado],
                        ['personaRec.nombre', 'like', '%'. $buscar . '%']
                    ])
                    ->orderBy('activities.start','desc')->paginate(12);

                }elseif($criterio == 'emisor'){
                    $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([
                        ['activities.status',$estado],
                        ['personaEmi.nombre', 'like', '%'. $buscar . '%']
                    ])
                    ->orderBy('activities.start','desc')->paginate(12);
                }else{
                    $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                    ->join('users AS emisor','emisor.id','=','activities.idemisor')
                    ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                    ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                    ->select('activities.id','activities.start','activities.end',
                        'activities.title','activities.content','activities.status',
                        'activities.idreceptor','activities.idemisor',
                        'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                    ->where([['activities.status',$estado],['activities.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('activities.start','desc')->paginate(12);
                }
            }else{
                $actividades = Activity::join('users AS receptor','receptor.id','=','activities.idreceptor')
                ->join('users AS emisor','emisor.id','=','activities.idemisor')
                ->leftjoin('personas AS personaRec', 'receptor.id','=','personaRec.id')
                ->leftjoin('personas AS personaEmi', 'emisor.id','=','personaEmi.id')
                ->select('activities.id','activities.start','activities.end',
                    'activities.title','activities.content','activities.status',
                    'activities.idreceptor','activities.idemisor',
                    'personaEmi.nombre as emisor','personaRec.nombre as receptor')
                ->where([['activities.status',$estado]])
                ->orderBy('activities.start','desc')->paginate(12);
            }
        }


        return [
            'pagination' => [
                'total'         => $actividades->total(),
                'current_page'  => $actividades->currentPage(),
                'per_page'      => $actividades->perPage(),
                'last_page'     => $actividades->lastPage(),
                'from'          => $actividades->firstItem(),
                'to'            => $actividades->lastItem(),
            ],
            'actividades' => $actividades
        ];


    }
    public function store(Request $request){

        if(!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        $activity = new Activity();
        $activity->start = $mytime;
        $activity->end = $request->end;
        $activity->title = $request->title;
        $activity->content = $request->content;
        $activity->idreceptor = $request->idreceptor;
        $activity->idemisor = \Auth::user()->id;
        $activity->status = '0';
        $activity->save();
    }
    public function update(Request $request){

        if(!$request->ajax()) return redirect('/');

        $activity = Activity::findOrFail($request->id);
        $activity->start = $request->start;
        $activity->end = $request->end;
        $activity->title = $request->title;
        $activity->content = $request->content;
        $activity->idreceptor = $request->idreceptor;
        $activity->idemisor = $request->idemisor;
        $activity->status = $request->status;
        $activity->save();

    }
    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $actividad = Activity::findOrFail($request->id);
        $actividad->status = '2';
        $actividad->save();
    }
    public function cambiarEstado(Request $request){
        if (!$request->ajax()) return redirect('/');
        $actividad = Activity::findOrFail($request->id);
        $actividad->status = $request->estado;
        $actividad->save();
    }
    public function getActivitiesUser(Request $request){

        if (!$request->ajax()) return redirect('/');
        $userid = \Auth::user()->id;
        $total =  Activity::where([['activities.idreceptor',$userid],['activities.status',0]])->count();
        return ['total' => $total];
    }
}

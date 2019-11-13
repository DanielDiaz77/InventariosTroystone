<?php

namespace App\Http\Controllers;
use App\Persona;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $usrol = \Auth::user()->idrol;

        if($usrol == 2){
            $usvend = \Auth::user()->id;

            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if($buscar==''){
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.telefono','personas.rfc','personas.tipo','personas.observacion','users.usuario as vendedor')
                ->where('personas.idusuario',$usvend)
                ->orderBy('id', 'desc')->paginate(12);
            }else{
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.telefono','personas.rfc','personas.tipo','personas.observacion','users.usuario as vendedor')
                ->where([
                    [$criterio, 'like', '%'. $buscar . '%'],
                    ['personas.idusuario',$usvend]
                ])
                ->orderBy('id', 'desc')->paginate(12);
            }
        }else{
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if($buscar==''){
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.telefono','personas.rfc','personas.tipo','personas.observacion','users.usuario as vendedor')
                ->orderBy('id', 'desc')->paginate(12);
            }else{
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.telefono','personas.rfc','personas.tipo','personas.observacion','users.usuario as vendedor')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('id', 'desc')->paginate(12);
            }
        }

        return [
            'pagination' => [
                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem(),
            ],
            'personas' => $personas,
            'rol' => $usrol
        ];
    }

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->ciudad = $request->ciudad;
        $persona->domicilio = $request->domicilio;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->rfc = $request->rfc;
        $persona->tipo = $request->tipo;
        $persona->observacion = $request->observacion;
        $persona->idusuario = \Auth::user()->id;
        $persona->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->ciudad = $request->ciudad;
        $persona->domicilio = $request->domicilio;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->rfc = $request->rfc;
        $persona->tipo = $request->tipo;
        $persona->idusuario = \Auth::user()->id;
        $persona->observacion = $request->observacion;
        $persona->save();
    }

    public function selectCliente(Request $request){

        if (!$request->ajax()) return redirect('/');

        $usrol = \Auth::user()->idrol;
        if($usrol == 2){
            $usvend = \Auth::user()->id;

            $filtro = $request->filtro;
            $clientes = Persona::where([
                ['nombre','like','%'.$filtro.'%'],
                ['idusuario',$usvend]
            ])
            ->orWhere([
                ['rfc','like','%'.$filtro.'%'],
                ['idusuario',$usvend]
            ])
            ->select('id','nombre','rfc','tipo')
            ->orderBy('nombre','asc')->get();
        }else{

            $filtro = $request->filtro;
            $clientes = Persona::where('nombre','like','%'.$filtro.'%')
                ->orWhere('rfc','like','%'.$filtro.'%')
                ->select('id','nombre','rfc','tipo')
                ->orderBy('nombre','asc')->get();
        }

        return ['clientes' => $clientes];
    }
}

<?php

namespace App\Http\Controllers;
use App\Persona;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $usrol = \Auth::user()->idrol;
        $usid = \Auth::user()->id;

        if($usrol == 2){
            $usvend = \Auth::user()->id;

            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if($buscar==''){
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo',
                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                ->where([['personas.idusuario',$usvend],['personas.active',1]])
                ->orderBy('id', 'desc')->paginate(12);
            }else{
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo',
                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                ->where([
                    [$criterio, 'like', '%'. $buscar . '%'],
                    ['personas.idusuario',$usvend],
                    ['personas.active',1]
                ])
                ->orderBy('id', 'desc')->paginate(12);
            }
        }else{
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            $estado = $request->status;

            if($buscar==''){
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo',
                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                ->where('personas.active',$estado)
                ->orderBy('id', 'desc')->paginate(12);
            }else{
                $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email',
                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo',
                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado]])
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
            'userrol' => $usrol,
            'userid' => $usid
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
        $persona->company = $request->company;
        $persona->tel_company = $request->tel_company;
        $persona->email = $request->email;
        $persona->rfc = $request->rfc;
        $persona->tipo = $request->tipo;
        $persona->observacion = $request->observacion;
        $persona->idusuario = $request->idusuario;
        $persona->cfdi = $request->cfdi;
        $persona->active = 1;
        /* $persona->idusuario = \Auth::user()->id; */
        $persona->save();
    }
    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->ciudad = $request->ciudad;
        $persona->domicilio = $request->domicilio;
        $persona->telefono = $request->telefono;
        $persona->company = $request->company;
        $persona->tel_company = $request->tel_company;
        $persona->email = $request->email;
        $persona->rfc = $request->rfc;
        $persona->tipo = $request->tipo;
        $persona->idusuario = $request->idusuario;
        /* $persona->idusuario = \Auth::user()->id; */
        $persona->cfdi = $request->cfdi;
        $persona->active = 1;
        $persona->observacion = $request->observacion;
        $persona->save();
    }

    public function desactivarCliente(Request $request){
        if(!$request->ajax()) return redirect('/');
        $cliente = Persona::findOrFail($request->id);
        $cliente->active = '0';
        $cliente->save();
    }
    public function activarCliente(Request $request){
        if(!$request->ajax()) return redirect('/');
        $cliente = Persona::findOrFail($request->id);
        $cliente->active = '1';
        $cliente->save();
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
            ->select('id','nombre','rfc','tipo','telefono','company','tel_company','observacion','cfdi')
            ->orderBy('nombre','asc')->get();
        }else{

            $filtro = $request->filtro;
            $clientes = Persona::where('nombre','like','%'.$filtro.'%')
                ->orWhere('rfc','like','%'.$filtro.'%')
                ->select('id','nombre','rfc','tipo','telefono','company','tel_company','observacion','cfdi')
                ->orderBy('nombre','asc')->get();
        }

        return ['clientes' => $clientes];
    }
}

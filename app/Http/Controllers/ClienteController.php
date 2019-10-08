<?php

namespace App\Http\Controllers;
use App\Persona;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $personas = Persona::orderBy('id', 'desc')->paginate(12);
        }else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(12);
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
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {
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

        $persona->save();
    }

    public function selectCliente(Request $request){

        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $clientes = Persona::where('nombre','like','%'.$filtro.'%')
            ->orWhere('rfc','like','%'.$filtro.'%')
            ->select('id','nombre','rfc')
            ->orderBy('nombre','asc')->get();

        return ['clientes' => $clientes];
    }
}

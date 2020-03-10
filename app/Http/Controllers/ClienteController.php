<?php

namespace App\Http\Controllers;
use App\Persona;
use App\Document;
use App\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $usrol = \Auth::user()->idrol;
        $usarea = \Auth::user()->area;
        $usid = \Auth::user()->id;

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $estado = $request->status;
        $zona = $request->zona;
        $actives = $request->actives;

        if($usrol == 1){
            if($buscar == ''){
                if($zona == ''){
                    if($estado == ''){
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }else{
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.active',$estado]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.active',$estado]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.active',$estado]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }
                }else{
                    if($estado == ''){
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }else{
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.active',$estado],['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.area',$zona]])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }
                }
            }
            else{
                if($zona == ''){
                    if($estado == ''){
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }else{
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.active',$estado],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }
                }else{
                    if($estado == ''){
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }else{
                        if($actives == ''){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->where([['personas.active',$estado],['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'Y'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNotNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }elseif($actives == 'N'){
                            $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                            ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                            ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                                'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                                'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                            ->whereNull('ventas.id')
                            ->where([['personas.active',$estado],['personas.area',$zona],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                            ->orderBy('id', 'desc')->paginate(12);
                        }
                    }
                }
            }
        }else{
            if($buscar == ''){
                if($estado == ''){
                    if($actives == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'Y'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'N'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($actives == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.active',$estado],['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'Y'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'N'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$usarea]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }
            else{
                if($estado == ''){
                    if($actives == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'Y'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'N'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($actives == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.active',$estado],['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'Y'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }elseif($actives == 'N'){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                            'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                            'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$usarea],['personas.'.$criterio, 'like', '%'. $buscar . '%']])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }
        }


        /* if($actives == ''){
            if($usrol == 2){
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.idusuario',$usvend],['personas.active',1]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.idusuario',$usvend],['personas.active',1],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1],
                            ['personas.area',$zona]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where('personas.active',$estado)
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }
        }elseif($actives == 'Y'){
            if($usrol == 2){
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.idusuario',$usvend],['personas.active',1]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.idusuario',$usvend],['personas.active',1],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1],
                            ['personas.area',$zona]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where('personas.active',$estado)
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNotNull('ventas.id')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }

        }elseif($actives == 'N'){
            if($usrol == 2){
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.idusuario',$usvend],['personas.active',1]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.idusuario',$usvend],['personas.active',1],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([
                            [$criterio, 'like', '%'. $buscar . '%'],
                            ['personas.idusuario',$usvend],
                            ['personas.active',1],
                            ['personas.area',$zona]
                        ])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }else{
                if($buscar==''){
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where('personas.active',$estado)
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }else{
                    if($zona == ''){
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }else{
                        $personas = Persona::leftjoin('users','personas.idusuario','=','users.id')
                        ->leftjoin('ventas','ventas.idcliente','=','personas.id')
                        ->select('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario as vendedor','users.id as idvendedor')
                        ->groupBy('personas.id','personas.nombre','personas.ciudad','personas.domicilio','personas.email','personas.num_documento',
                        'personas.company','personas.tel_company','personas.telefono','personas.rfc','personas.tipo','personas.area',
                        'personas.active','personas.cfdi','personas.observacion','users.usuario','users.id')
                        ->whereNull('ventas.id')
                        ->where([[$criterio, 'like', '%'. $buscar . '%'],['personas.active',$estado],['personas.area',$zona]])
                        ->orderBy('id', 'desc')->paginate(12);
                    }
                }
            }
        } */


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

        /* $dt = Carbon::now()->format('Ynd'); */ //Generar la fecha en formato 20191226.

        /* $noCliente = 'C-'+ $request->num_documento; */

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
        $persona->area = $request->area;
        /* $persona->idusuario = \Auth::user()->id; */
        $persona->save();
    }

    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');

        $dt = Carbon::now()->format('Ynd'); //Generar la fecha en formato 20191226.

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
        $persona->area = $request->area;
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
            $usarea = \Auth::user()->area;
            $usvend = \Auth::user()->id;

            $filtro = $request->filtro;

            $clientes = Persona::where([
                ['nombre','like','%'.$filtro.'%'],
                ['area',$usarea],
                ['active',1]
            ])
            ->orWhere([
                ['rfc','like','%'.$filtro.'%'],
                ['area',$usarea],
                ['active',1]
            ])
            ->select('id','nombre','rfc','tipo','telefono','company','tel_company','observacion','cfdi','num_documento')
            ->orderBy('nombre','asc')->get();
        }else{

            $filtro = $request->filtro;
            $clientes = Persona::where([['nombre','like','%'.$filtro.'%'],['active',1]])
                ->orWhere([['rfc','like','%'.$filtro.'%'],['active',1]])
                ->select('id','nombre','rfc','tipo','telefono','company','tel_company','observacion','cfdi','num_documento')
                ->orderBy('nombre','asc')->get();
        }

        return ['clientes' => $clientes];
    }

    public function filesUppload(Request $request){

        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            //The name of the directory that we need to create.
            $directoryName = 'clientesfiles';

            if(!is_dir($directoryName)){
                //Directory does not exist, so lets create it.
                mkdir($directoryName, 0777);
            }

            $archivos = $request->filesdata;//Array de archivos


            $docs = array();
            foreach($archivos as $ar=>$file){

                $exploded = explode(',', $file['url']);
                $decoded = base64_decode($exploded[1]);
                $extn = explode('/', $file['tipo']);
                $extension = $extn[1];
                $fileName = str_random().'.'.$extension;
                $path = public_path($directoryName).'/'.$fileName;
                file_put_contents($path,$decoded);

                //array_push($docs,$fileName);

                $docum = new Document(['url' => $fileName, 'tipo' => $extension ]);
                $persona = Persona::findOrFail($request->id); //ID dueño de los archivos
                $persona->documents()->save($docum);
                DB::commit();

                /* array_push($docs,$docum); */
            }
           /*  $cliente = $persona->nombre;
            return ['ArrayDocs' => $docs,'Cliente' => $cliente]; */


        }catch(Exception $e){
            DB::rollBack();
        }
    }

    public function getDocs(Request $request){
        if (!$request->ajax()) return redirect('/');
        $cliente = Persona::findOrFail($request->id); //ID dueño de los archivos
        $files = $cliente->documents()->get();

        return [
            'documentos' => $files
        ];
    }

    public function eliminarDoc(Request $request){
        if (!$request->ajax()) return redirect('/');

        //The name of the directory that we need to create.
        $directoryName = 'clientesfiles';

        try{
            DB::beginTransaction();

            $doc= Document::findOrFail($request->id);
            $img = $doc->url;


            if($img != null){
                $image_path = public_path($directoryName).'/'.$img;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            $doc->delete();
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }

    public function crearComment(Request $request){
        if (!$request->ajax()) return redirect('/');
        $userid = \Auth::user()->id;
        try{
            DB::beginTransaction();
            $comment = new Comment(['user_id' => $userid, 'body' => $request->body]);
            $persona = Persona::findOrFail($request->id); //Cliente a comentar
            $persona->comments()->save($comment);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function getComments(Request $request){

        if (!$request->ajax()) return redirect('/');

        $persona = Persona::findOrFail($request->id); //ID persona comentada

        $comments = $persona->comments()
        ->join('users','users.id','comments.user_id')
        ->leftjoin('personas', 'users.id','=','personas.id')
        ->select('comments.id','comments.user_id as user','comments.body','comments.updated_at as fecha','personas.nombre')
        ->orderBy('comments.updated_at','desc')
        ->get();

        return [
            'comentarios' => $comments
        ];

    }
    public function editComment(Request $request){
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $comm= Comment::findOrFail($request->id);
            $comm->body = $request->body;
            $comm->save();

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function deleteComment(Request $request){
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $comm = Comment::findOrFail($request->id);
            $comm->delete();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
}

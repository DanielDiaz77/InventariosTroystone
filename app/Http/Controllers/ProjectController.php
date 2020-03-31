<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectsExport;
use App\Document;
use App\Project;
use App\Deposit;
use App\Venta;
use App\User;
use App\Credit;

class ProjectController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $estado = $request->estado;
        $entrega = $request->estadoEntrega;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usrol = \Auth::user()->idrol;

        if($estado == ''){
            if($entrega == ''){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'parcial'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado_parcial',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado_parcial',1],['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado_parcial',1],['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'completa'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado',1],['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado',1],['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'no_entregado'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.entregado',0],['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['clientes.nombre', 'like', '%'. $buscar . '%'],['projects.entregado',0],
                        ['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.'.$criterio, 'like', '%'. $buscar . '%'],['projects.entregado',0],
                        ['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }
        }elseif($estado == 'Registradas'){
            if($entrega == ''){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'parcial'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado_parcial',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado_parcial',1],
                        ['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado_parcial',1],
                        ['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'completa'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado',1],
                        ['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado',1],
                        ['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'no_entregado'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.entregado',0],
                        ['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['clientes.nombre', 'like', '%'. $buscar . '%'],
                        ['projects.entregado',0],['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Registrado'],['projects.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['projects.entregado',0],['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }
        }elseif($estado == 'Anuladas'){
            if($entrega == ''){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'parcial'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado_parcial',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado_parcial',1],
                        ['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado_parcial',1],
                        ['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'completa'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado',1]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado',1],
                        ['clientes.nombre', 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado',1],
                        ['projects.'.$criterio, 'like', '%'. $buscar . '%']])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }elseif($entrega == 'no_entregado'){
                if($buscar == ''){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.entregado',0],
                        ['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }elseif($criterio == 'cliente'){
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['clientes.nombre', 'like', '%'. $buscar . '%'],
                        ['projects.entregado',0],['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }else{
                    $projects = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
                    ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
                    ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
                        'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
                        'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
                        'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
                        'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
                        'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
                        'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
                        'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
                        'projects.created_at as registro')
                    ->where([['projects.estado','Anulado'],['projects.'.$criterio, 'like', '%'. $buscar . '%'],
                        ['projects.entregado',0],['projects.entregado_parcial',0]])
                    ->orderBy('projects.id', 'desc')->paginate(12);
                }
            }
        }

        return [
            'pagination' => [
                'total'        => $projects->total(),
                'current_page' => $projects->currentPage(),
                'per_page'     => $projects->perPage(),
                'last_page'    => $projects->lastPage(),
                'from'         => $projects->firstItem(),
                'to'           => $projects->lastItem(),
            ],
            'projects' => $projects,
            'userrol' => $usrol
        ];
    }
    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');

        $presupuestos = $request->presupuestos;//Array de presupuestos seleccionados (id)

        try{
            DB::beginTransaction();

            $project = new Project();
            $project->idcliente = $request->idcliente;
            $project->idusuario = \Auth::user()->id;
            $project->tipo_comprobante = $request->tipo_comprobante;
            $project->num_comprobante = $request->num_comprobante;
            $project->title = $request->title;
            $project->content =  $request->content;
            $project->inicio = $request->inicio;
            $project->fin = $request->fin;
            $project->impuesto =  $request->impuesto;
            $project->total =  $request->total;
            $project->adeudo =  $request->total;
            $project->forma_pago =  $request->forma_pago;
            $project->lugar_entrega =  $request->lugar_entrega;
            $project->estado = 'Registrado';
            $project->pagado = 0;
            $project->pagado_parcial = 0;
            $project->entregado = 0;
            $project->entregado_parcial = 0;
            $project->flete = $request->flet;
            $project->instalacion = $request->insta;
            $project->area =  $request->area;
            $project->tipo_facturacion =  $request->tipo_facturacion;
            $project->observacion = $request->observacion;
            $project->observacionpriv = $request->observacionpriv;
            $project->save();
            $project->ventas()->attach($presupuestos);

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function update(Request $request){

        if(!$request->ajax()) return redirect('/');

        $presupuestos = $request->presupuestos;//Array de presupuestos seleccionados (id)

        try{
            DB::beginTransaction();

            $project = Project::findOrFail($request->id);
            $project->idcliente = $request->idcliente;
            $project->idusuario = \Auth::user()->id;
            $project->tipo_comprobante = $request->tipo_comprobante;
            $project->num_comprobante = $request->num_comprobante;
            $project->title = $request->title;
            $project->content =  $request->content;
            $project->inicio = $request->inicio;
            $project->fin = $request->fin;
            $project->impuesto =  $request->impuesto;
            $project->total =  $request->total;
            $project->adeudo =  $request->total;
            $project->forma_pago =  $request->forma_pago;
            $project->lugar_entrega =  $request->lugar_entrega;
            $project->flete = $request->flet;
            $project->instalacion = $request->insta;
            $project->area =  $request->area;
            $project->tipo_facturacion =  $request->tipo_facturacion;
            $project->save();
            $project->ventas()->sync($presupuestos);

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function getVentas(Request $request){
        if (!$request->ajax()) return redirect('/');
        $project = Project::findOrFail($request->id); //ID del project
        $ventas = $project->ventas()
        ->leftjoin('personas AS clientes', 'clientes.id','=','ventas.idcliente')
        ->leftJoin('users','ventas.idusuario','=','users.id')
        ->select('ventas.id as idventa','ventas.tipo_comprobante','ventas.num_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
            'ventas.moneda','ventas.tipo_cambio','ventas.observacion','ventas.forma_pago',
            'ventas.tiempo_entrega','ventas.lugar_entrega','ventas.entregado','ventas.banco',
            'ventas.entrega_parcial','ventas.tipo_facturacion', 'ventas.pagado','users.usuario',
            'ventas.num_cheque','clientes.nombre as cliente','ventas.file','ventas.observacionpriv',
            'ventas.facturado','ventas.factura_env','ventas.pago_parcial','ventas.adeudo')
        ->get();
        return [
            'ventas' => $ventas
        ];
    }
    public function desactivar(Request $request){

        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $project = Project::findOrFail($request->id);
            $project->estado = 'Anulado';
            $project->entregado = 0;
            $project->entregado_parcial = 0;
            $project->pagado = 0;
            $project->pagado_parcial = 0;
            $project->save();

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function cambiarEntrega(Request $request){
        if (!$request->ajax()) return redirect('/');
        $project = Project::findOrFail($request->id);
        $project->entregado = $request->entregado;
        $project->entregado_parcial = 0;
        $project->save();
    }
    public function cambiarEntregaParcial(Request $request){
        if (!$request->ajax()) return redirect('/');
        $project = Project::findOrFail($request->id);
        $project->entregado_parcial = $request->entregado_parcial;
        $project->entregado = 0;
        $project->save();
    }
    public function cambiarPagado(Request $request){

        if (!$request->ajax()) return redirect('/');

        if($request->pagado == 1){
            $project = Project::findOrFail($request->id);
            $project->pagado = $request->pagado;
            $project->adeudo = 0;
            $project->save();
        }else{
            $project = Project::findOrFail($request->id);
            $project->pagado = $request->pagado;
            $project->adeudo = $project->total;
            $project->save();
        }


    }
    public function actualizarObservacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $project = Project::findOrFail($request->id);
        $project->observacion = $request->observacion;
        $project->save();
    }
    public function actualizarObservacionPriv(Request $request){
        if (!$request->ajax()) return redirect('/');
        $project = Project::findOrFail($request->id);
        $project->observacionpriv = $request->observacionpriv;
        $project->save();
    }
    public function crearDeposit(Request $request){

        if (!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        $project = Project::findOrFail($request->id); //Project a depositar

        $adeudoAct = $project->adeudo;

        if($request->total < $adeudoAct){
            try{
                DB::beginTransaction();
                $project->adeudo = $project->adeudo - $request->total;
                $project->pagado_parcial = 1;
                $project->pagado = 0;
                $project->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime,
                    'forma_pago' => $request->forma_pago]);
                $project->deposits()->save($deposit);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }elseif($request->total == $adeudoAct){
            try{
                DB::beginTransaction();
                $project->adeudo = 0;
                $project->pagado_parcial = 1;
                $project->pagado = 1;
                $project->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime,
                    'forma_pago' => $request->forma_pago]);
                $project->deposits()->save($deposit);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }
    }
    public function getDeposits(Request $request){

        if (!$request->ajax()) return redirect('/');

        $project = Project::findOrFail($request->id); //ID project y sus depositos

        $deposits = $project->deposits()
        ->select('deposits.id','deposits.total','deposits.fecha_hora as fecha','deposits.forma_pago')
        ->orderBy('deposits.fecha_hora','desc')
        ->get();

        return [
            'abonos' => $deposits
        ];

    }
    public function deleteDeposit(Request $request){
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $deposit = Deposit::findOrFail($request->id);
            //$deposit->delete();

            if($deposit->forma_pago == 'Nota de crédito'){
                $creditos = $deposit->credits()->select('credits.id')->get();
                foreach($creditos as $det){
                    $credit = Credit::findOrFail($det['id']);
                    $credit->estado = 'Vigente';
                    $credit->save();
                }
                $deposit->delete();
            }else{
                $deposit->delete();
            }

            $project = Project::findOrFail($request->idproject);
            $numDeposits = $project->deposits()->count();

            if($numDeposits <= 0){
                $project->pagado_parcial = 0;
                $project->pagado = 0;
                $project->adeudo = $project->total;
                $project->save();
            }else{
                $project->adeudo = $project->adeudo + $request->total;
                $project->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function refreshProject(Request $request){
        if (!$request->ajax()) return redirect('/');
        $usrol = \Auth::user()->idrol;

        $project = Project::leftjoin('personas AS creador','creador.id','=','projects.idusuario')
        ->leftjoin('personas AS clientes', 'clientes.id','=','projects.idcliente')
        ->select('projects.id','projects.tipo_comprobante','projects.num_comprobante','projects.title',
            'projects.content','projects.inicio','projects.fin','projects.impuesto','projects.total',
            'projects.adeudo','projects.forma_pago','projects.lugar_entrega','projects.estado',
            'projects.pagado','projects.pagado_parcial','projects.entregado','projects.entregado_parcial',
            'projects.flete','projects.instalacion','projects.area','projects.tipo_facturacion',
            'projects.observacion','projects.observacionpriv','creador.nombre as usuario',
            'clientes.nombre as cliente','clientes.tipo','clientes.telefono','clientes.rfc',
            'clientes.cfdi', 'clientes.company','clientes.tel_company','clientes.id as idcliente',
            'projects.created_at as registro')
        ->where('projects.id',$request->id)
        ->first();
        return [ 'project' => $project , 'userrol' => $usrol ];
    }
    public function filesUppload(Request $request){

        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            //The name of the directory that we need to create.
            $directoryName = 'projectfiles';

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

                $docum = new Document(['url' => $fileName, 'tipo' => $extension ]);
                $project = Project::findOrFail($request->id); //ID project
                $project->documents()->save($docum);
                DB::commit();
            }

        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function getDocs(Request $request){
        if (!$request->ajax()) return redirect('/');

        $project = Project::findOrFail($request->id); //ID dueño de los archivos
        $files = $project->documents()->get();

        return [
            'documentos' => $files
        ];
    }
    public function eliminarDoc(Request $request){
        if (!$request->ajax()) return redirect('/');

        //The name of the directory that we need to create.
        $directoryName = 'projectfiles';

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
    public function crearDepositCredit(Request $request){

        if (!$request->ajax()) return redirect('/');

        $mytime = Carbon::now('America/Mexico_City');

        $project = Project::findOrFail($request->id); //Project a depositar

        $adeudoAct = $project->adeudo;

        $creditos = $request->creditos;

        if($request->total < $adeudoAct){
            try{
                DB::beginTransaction();
                $project->adeudo = $project->adeudo - $request->total;
                $project->pagado_parcial = 1;
                $project->pagado = 0;
                $project->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime,
                    'forma_pago' => $request->forma_pago]);
                $project->deposits()->save($deposit);

                $deposit->credits()->attach($creditos);
                foreach($creditos as $det){
                    $credit = Credit::findOrFail($det);
                    $credit->estado = 'Abonada';
                    $credit->save();
                }

                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }elseif($request->total == $adeudoAct){
            try{
                DB::beginTransaction();
                $project->adeudo = 0;
                $project->pagado_parcial = 1;
                $project->pagado = 1;
                $project->save();
                $deposit = new Deposit(['total' => $request->total,'fecha_hora' => $mytime,
                    'forma_pago' => $request->forma_pago]);
                $project->deposits()->save($deposit);

                $deposit->credits()->attach($creditos);
                foreach($creditos as $det){
                    $credit = Credit::findOrFail($det);
                    $credit->estado = 'Abonada';
                    $credit->save();
                }
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
            }
        }
    }

    public function ListarExcel(Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        $usuarios = $request->usuarios;
        $ArrUsuarios = explode(",",$usuarios);

        return Excel::download(new ProjectsExport($inicio,$fin,$ArrUsuarios), 'presupuestosEspeciales-'.$inicio.'-'.$fin.'.xlsx');
    }
}

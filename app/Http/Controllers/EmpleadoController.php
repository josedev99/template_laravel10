<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\AreaDepartamentoEmp;
use App\Models\CategoriaExamen;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Empleado;
use App\Models\Jornada;
use App\Models\Laboratorio;
use App\Models\Sucursal;
use App\Models\FactorRiesgo;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index(){
        return  view('Empleado.index');
    }

    public function listar_empleados(){
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select("SELECT e.*,s.nombre as sucursal,s.telefono as tel_suc,a.nombre area, emp.nombre as empresa FROM `empleados` as e inner join empresas as emp on e.empresa_id=emp.id INNER join sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id inner join area_emps as a on e.area_depto_id=a.id and e.empresa_id=a.id_empresa WHERE e.empresa_id = ? ORDER BY e.id asc;",[$empresa_id]);
        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $genero = '';
            if($row->genero == "M"){
                $genero = "Masculino";
            }else if($row->genero == "F"){
                $genero = "Femenino";
            }else{
                $genero = $row->genero;
            }
            $iconInhabilitar = ($row->estado == '1') ? '<i class="bi bi-person-slash text-danger"></i>' : '<i class="bi bi-person-check text-success"></i>';

            $labelBtnStatusEmp = ($row->estado == '1') ? 'Deshabilitar colaborador' : 'Habilitar colaborador';

            $sub_array[] = $contador;
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = $row->categoria;
            $sub_array[] = ucwords(strtolower(Lucipher::Descipher($row->nombre)));
            $sub_array[] = $row->telefono;
            $sub_array[] = ucwords(strtolower($row->area));
            $sub_array[] = $genero;
            $sub_array[] = ucwords(strtolower($row->sucursal));
            $sub_array[] = '
            <button data-empleado_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. Lucipher::Descipher($row->nombre) .'" data-empresa="'. $row->empresa .'" data-sucursal="'. $row->sucursal .'" title="Registrar examenes preingreso" class="btn btn-outline-info btn-sm" onclick="addPreingresoExamen(this)" style="border:none;font-size:18px"><i class="bi bi-file-earmark-plus"></i></button>
            <button data-empleado_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. Lucipher::Descipher($row->nombre) .'" data-empresa="'. $row->empresa .'" data-sucursal="'. $row->sucursal .'" title="Registrar pos-incapacidad" class="btn btn-outline-success btn-sm" onclick="ingPosIncapacidad(this)" style="border:none;font-size:18px"><i class="bi bi-box2-heart-fill"></i></button>
            <button data-ref="'. Lucipher::Cipher($row->id) .'" title="Actualizar información del empleado" class="btn btn-outline-info btn-sm" onclick="editEmpleado(this)" style="border:none;font-size:18px"><i class="bi bi-person-gear"></i></button>
            <button data-empleado_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. Lucipher::Descipher($row->nombre) .'" data-empresa="'. $row->empresa .'" data-status="'. Lucipher::Cipher($row->estado) .'" title="'.$labelBtnStatusEmp.'" class="btn btn-outline btn-sm" onclick="toggleEmpleadoStatus(this)" style="border:none;font-size:18px">'.$iconInhabilitar.'</button>
            <button data-ref="'. Lucipher::Cipher($row->id) .'" title="Eliminar empleado" class="btn btn-outline-danger btn-sm btn-destroy" onclick="destroyEmp(this)" style="border:none;font-size:18px"><i class="bi bi-person-fill-x"></i></button>
            ';

            $data[] = $sub_array;
            $contador ++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function save_empleado(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');
        
        $validateData = request()->validate([
            'categoria_empleado' => 'required|string|min:1|max:50',
            'codigo_empleado' => 'required|string|min:1|max:25',
            'nombre_empleado' => 'required|string|min:4|max:200',
            'telefono' => 'required|string|min:1|max:15',
            'genero_emp' => 'required|string|min:1|max:4',
            'fecha_nac_emp' => 'required|string|min:8|max:15',
            'fecha_ing_emp' => 'required|string|min:8|max:15',
            'depto_emp' => 'required|string|min:1|max:150',
            'cargo_emp' => 'required|string|min:1|max:150',
            'sucursal_emp' => 'required|string|min:1'
        ]);

        //validar si es ediccion o nuevo registro
        $numero_afiliacion = (isset($validateData['numero_afiliacion'])) ? Lucipher::Cipher(trim($validateData['numero_afiliacion'])): '';
        $codigo_empleado = strtoupper(trim($validateData['codigo_empleado']));
        $data = [
            'categoria' => $validateData['categoria_empleado'],
            'nombre' => Lucipher::Cipher(trim(strtoupper($validateData['nombre_empleado']))),
            'genero' => $validateData['genero_emp'],
            'telefono' => $validateData['telefono'],
            'codigo_empleado' => $codigo_empleado,
            'no_afiliacion' => $numero_afiliacion,
            'fecha_ingreso' => date('Y-m-d',strtotime(str_replace('/','-',$validateData['fecha_ing_emp']))),
            'area_depto_id' => $validateData['depto_emp'],
            'cargo' => '-',
            'cargo_id' => $validateData['cargo_emp'],
            'fecha_nacimiento' => date('Y-m-d',strtotime(str_replace('/','-',$validateData['fecha_nac_emp']))),
            'fecha' => $date,
            'hora' => $hora
        ];
        $empleado_id = request()->input('empleado_id');
        if($empleado_id == ''){
            //Validar si ya existe
            $exists = Empleado::where('codigo_empleado',$codigo_empleado)->where('empresa_id', $empresa_id)->exists();
            if($exists){
                return response()->json([
                    'status' => 'exists',
                    'message' => 'El colaborador ya esta registrado.'
                ]);
            }

            $merge_data = array_merge($data, [
                'empresa_id' => $empresa_id,
                'sucursal_id' => $validateData['sucursal_emp'],
                'usuario_id' => $usuario_id,
            ]);
            $result_save = Empleado::create($merge_data);
            $data_save = [
                'id' => Lucipher::Cipher($result_save->id),
                'codigo_empleado' => $result_save->codigo_empleado,
                'nombre' => Lucipher::Descipher($result_save->nombre),
                'telefono' => $result_save->telefono
            ];

            $message = 'El empleado se ha registrado exitosamente.';
        }else{
            
            $empleado_id = Lucipher::Descipher($empleado_id);
            $result_save = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->update($data);

            $data_save = [];
            $message = 'El empleado se ha actualizado exitosamente.';
        }

        if($result_save){
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'results' => $data_save
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar el empleado.',
            'results' => []
        ]);
    }

    public function get_empleado_by_id(){
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = Lucipher::Descipher(request()->input('ref_emp'));
        $data = Empleado::where('id',$ref_emp)->where('empresa_id',$empresa_id)->get();
        $arrayData = [];
        foreach($data as $item){
            $arrayData['categoria'] = $item['categoria'];
            $arrayData['nombre'] = Lucipher::Descipher($item['nombre']);
            $arrayData['genero'] = $item['genero'];
            $arrayData['telefono'] = $item['telefono'];
            $arrayData['codigo_empleado'] = $item['codigo_empleado'];
            $arrayData['no_afiliacion'] = Lucipher::Descipher($item['no_afiliacion']);
            $arrayData['fecha_ingreso'] = date('d/m/Y',strtotime($item['fecha_ingreso']));
            $arrayData['area_depto_id'] = $item['area_depto_id'];
            $arrayData['cargo_id'] = $item['cargo_id'];
            $arrayData['fecha_nacimiento'] = date('d/m/Y',strtotime($item['fecha_nacimiento']));
            $arrayData['fecha'] = $item['fecha'];
            $arrayData['hora'] = $item['hora'];
            $arrayData['empresa_id'] = $item['empresa_id'];
            $arrayData['sucursal_id'] = $item['sucursal_id'];
            $arrayData['usuario_id'] = $item['usuario_id'];
        }
        return response()
            ->json(($arrayData))
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    function destroy_empleado(){
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = Lucipher::Descipher(request()->input('empleado_id'));
        //validaciones para remover empleado
        //validar si no tiene consulta
        $exists_consulta = Consulta::where('empleado_id',$ref_emp)->where('empresa_id',$empresa_id)->exists();
        
        if($exists_consulta){
            return response()->json([
                'status' => 'warning',
                'message' => 'El colaborador ya tiene consultas registradas.'
            ]);
        }

        $result = Empleado::where('id',$ref_emp)->where('empresa_id',$empresa_id)->delete();
        if($result){
            return response()->json([
                'status' => 'success',
                'message' => 'Empleado eliminado exitosamente.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de eliminar el registro.'
            ])
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    //DATOS

    public function indexPerfilVisual(){
        $empresa_id = Auth::user()->empresa_id;

        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilVisual.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }



    public function indexPerfilLabClinic(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilLabClinico.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }

    public function indexPerfilAudiometria(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilAudiometria.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }
    public function indexPerfilEspirometria(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilEspirometria.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }
    public function indexPerfilRayosx(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilRayosx.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }

    public function indexPerfilElectrocardiograma(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilElectrocrdiograma.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }

    public function indexPerfilcomplementarios(){
        $empresa_id = Auth::user()->empresa_id;
        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        $laboratorios = Laboratorio::where('empresa_id',$empresa_id)->get();
        //jornadas
        $jornadas = Jornada::where('empresa_id',$empresa_id)->orderBy('id','desc')->get();

        $response = response()->view('PerfilExComplementarios.index',compact('sucursales','areas_depto','cat_examenes','laboratorios','jornadas'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }


    public function verifyEmp(){
        $empresa_id = Auth::user()->empresa_id;

        $codigo_empleado = Lucipher::Descipher(request()->input('codigo_empleado'));
        $cita_id = Lucipher::Descipher(request()->input('cita_id'));

        if($codigo_empleado && $cita_id){
            $cita = Cita::where('id',$cita_id)->where('codigo_empleado',$codigo_empleado)->where('empresa_id',$empresa_id)->first();
            $exists = Empleado::where('codigo_empleado',$codigo_empleado)->where('empresa_id',$empresa_id)->select('id','codigo_empleado','nombre','telefono')->first();

            if($exists){
                //validar si existe cita
                $fecha_inicio_sintoma = '';
                $motivo = '';
                if($cita){
                    $fecha_inicio_sintoma = ($cita->fecha_inicio_sintoma != "") ? str_replace('-','/',date('d-m-Y',strtotime($cita->fecha_inicio_sintoma))) : '';
                    $motivo = $cita->motivo;
                }

                //validar si no tiene consultas en proceso
                $exists_consulta = Consulta::where('estado','en proceso')->where('empleado_id',$exists->id)->where('empresa_id',$empresa_id)->exists();
                if($exists_consulta){
                    return  [
                        'status' => 'exists_consult_proceso',
                        'message' => 'El colaborador tiene una consulta en proceso.'
                    ];
                }
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'El colaborador ya existe.',
                    'results' => [
                        'id' => Lucipher::Cipher($exists->id),
                        'nombre' => Lucipher::Descipher($exists->nombre),
                        'codigo_empleado' => $exists->codigo_empleado,
                        'telefono' => $exists->telefono,
                        'fecha_inicio_sintoma' => $fecha_inicio_sintoma,
                        'motivo' => $motivo
                    ]
                ]);
            }else{
                $cita->nombre = Lucipher::Descipher($cita->nombre);
                return response()->json([
                    'status' => 'not-data',
                    'message' => 'Informacion de la cita.',
                    'results' => $cita
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Se ha detectados datos alterados.'
        ]);
    }



    public function listar_dep(){
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select("SELECT * FROM `area_departamento_emps` WHERE empresa_id = ? ORDER BY id asc;",[$empresa_id]);
        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = Lucipher::Descipher($row->departamento);
            $data[] = $sub_array;
            $contador ++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function getDeptoCantEmp(){
        $empresa_id = Auth::user()->empresa_id;
        $area_deptos = DB::select("select a.id,a.departamento,COALESCE(count(e.id),0) as cantidad_emp from area_departamento_emps as a left join empleados as e on a.id=e.area_depto_id and a.empresa_id=e.empresa_id and e.estado != '0' where a.empresa_id = ? group by a.departamento ORDER BY a.id DESC;",[$empresa_id]);
        $array_final = [];
        foreach ($area_deptos as $value) {
            $array = [];
            $array["id"] = $value->id;
            $array['isCheck'] = false; //permite para identificar en frontend
            $array["area_depto"] = Lucipher::Descipher($value->departamento);
            $array["cantidad"] = $value->cantidad_emp;

            $factor_riesgos = DB::select("SELECT ddr.factor_riesgo_id,fr.nombre FROM `det_depto_riesgos` as ddr INNER JOIN factor_riesgos as fr on ddr.factor_riesgo_id=fr.id and ddr.empresa_id=fr.empresa_id where ddr.area_depto_id = ? and ddr.empresa_id = ?;",[$value->id,$empresa_id]);
            $array["riesgos"] = $factor_riesgos;
            //examenes
            $examenes = DB::select("SELECT e.id as examen_id,dpr.riesgo_id,e.nombre as examen,dpr.categoria FROM `det_depto_riesgos` as ddr INNER JOIN det_perfil_riesgos as dpr on ddr.factor_riesgo_id=dpr.riesgo_id and ddr.empresa_id=dpr.empresa_id INNER JOIN examenes as e on dpr.examen_id=e.id and dpr.empresa_id=e.empresa_id and dpr.categoria = 'laboratorio clinico' where ddr.area_depto_id = ? and ddr.empresa_id = ? UNION SELECT pe.id as examen_id,dpr.riesgo_id,pe.nombre as examen,dpr.categoria FROM `det_depto_riesgos` as ddr INNER JOIN det_perfil_riesgos as dpr on ddr.factor_riesgo_id=dpr.riesgo_id and ddr.empresa_id=dpr.empresa_id INNER JOIN pruebas_especiales as pe on dpr.examen_id=pe.id and dpr.empresa_id=pe.id_empresa and dpr.categoria in ('especialidades','complementarios') where ddr.area_depto_id = ? and ddr.empresa_id = ?;",[$value->id,$empresa_id,$value->id,$empresa_id]);
            $array["examenes"] = $examenes;

            $array_final[] = $array;
        }
        return response()->json($array_final);
    }

    public function toggleEmpleadoStatus(){
        $empresa_id = Auth::user()->empresa_id;

        $empleado_id = (int)Lucipher::Descipher(request()->input('empleado_id'));
        $estado = (int)Lucipher::Descipher(request()->input('status'));
        if($empleado_id){
            //validacion de estado de empleado ::: 0: desactivado | 1: Activo;
            $estadoEmp = ($estado == 1) ? 0 : 1;
            Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                'estado' => $estadoEmp
            ]);
            $message = ($estado == 1) ? 'El colaborador se ha deshabilitado exitosamente.' : 'El colaborador se ha habilitado exitosamente.';
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado. Intente nuevamente.'
            ]);
        }
    }
    /**
     * Obtener datos del empleado:: columnas: id,nombre,genero,edad
     */
    public function getInfoPacienteById(){
        $empresa_id = Auth::user()->empresa_id;

        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        if($empleado_id){
            $datos = Empleado::where('id',$empleado_id)->where('estado','!=','-1')->where('empresa_id',$empresa_id)->get();
            $empleado_data = [];
            foreach($datos as $item){
                $array = [];
                $array['id'] = $item['id'];
                $array['nombre'] = Lucipher::Descipher($item['nombre']);
                $array['genero'] = $this->getGeneroEmpleado($item['genero']);
                $array['fecha_nacimiento'] = $item['fecha_nacimiento'];
                $array['edad'] = $this->calcularEdad($item['fecha_nacimiento']);
                $empleado_data = $array;
            }
            return response()->json($empleado_data);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al obtener los datos.'
        ]);
    }

    public function calcularEdad($fechaNacimiento) {
        date_default_timezone_set('America/El_Salvador');
        $fechaNac = new DateTime($fechaNacimiento);
        $hoy = new DateTime(); // Fecha actual

        $edad = $hoy->diff($fechaNac);
        return $edad->y;
    }

    public function getGeneroEmpleado($prefijoGenero){
        $stringGenero = '';
        if(strtoupper($prefijoGenero) === "M"){
            $stringGenero = 'MASCULINO';
        }else if(strtoupper($prefijoGenero) === "F"){
            $stringGenero = 'FEMENINO';
        }else if(strtoupper($prefijoGenero) === "OTRO" || strtoupper($prefijoGenero) === "OTROS"){
            $stringGenero = 'OTROS';
        }
        return $stringGenero;
    }


































    
}

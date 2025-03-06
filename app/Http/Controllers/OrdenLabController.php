<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\DetOrdenLab;
use App\Models\Empresa;
use App\Models\OrdenLab;
use App\Models\OrdenLabJornada;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class OrdenLabController extends Controller
{
    public function save_orden(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $data_form = request()->except('search_examen');
        $data_form['data_examenes'] = json_decode($data_form['data_examenes']);
        //validacion de cantidad de examenes
        if(count($data_form['data_examenes']) === 0){
            return [
                'status' => 'error',
                'message' => 'No ha seleccionado ningun examen para este paciente'
            ];
        }
        //save orden lab
        DB::beginTransaction();
        try{
            $numero_orden = $this->get_correlativo_orden();
            $empleado_id = Lucipher::Descipher($data_form['emp_id']);
            $save_orden_lab = OrdenLab::create([
                'numero_orden' => $numero_orden,
                'fecha' => $date,
                'hora' => $hora,
                'estado' => '0',
                'estado_eval' => 'Sin evaluar',
                'empleado_id' => $empleado_id,
                'empresa_id' => $empresa_id,
                'sucursal_id' => $sucursal_id,
                'usuario_id' => $usuario_id
            ]);
            foreach($data_form['data_examenes'] as $item){
                DetOrdenLab::create([
                    'numero_orden' => $numero_orden,
                    'estado' => '0',
                    'empleado_id' => $empleado_id,
                    'categoria_id' => $item->categoria_id,
                    'examen_id' => $item->examen_id,
                    'orden_lab_id' => $save_orden_lab->id
                ]);
            }
            //validar si existe jornada
            if($data_form['jornada_orden'] !== null){
                OrdenLabJornada::create([
                    'orden_lab_id' => $save_orden_lab->id,
                    'jornada_id' => $data_form['jornada_orden']
                ]);
            }
            DB::commit();
            return [
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente la orden de examenes.',
                'results' => [
                    'id' => Lucipher::Cipher($save_orden_lab->id)
                ]
            ];
        }catch(Exception $err){
            DB::rollBack();
            return [
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de registrar la orden.'
            ];
        }
    }

    public function get_correlativo_orden(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
		$mes = date('m');
		$year = date('Y');
		$anio = substr($year, 2, 5);

        $month_year = "%" . $year . "-". $mes . "%";
        
        $data = DB::select("select numero_orden from orden_labs where fecha like ? and empresa_id = ? order by id desc limit 1", [$month_year,$empresa_id]);
        ////////OBTENEMOS EL CORRELATIVO //////
        if (is_array($data) and count($data) > 0) {
            foreach ($data as $row) {
                $numero_orden = substr($row->numero_orden, 4, 15) + 1;
                $codigo = $mes . $anio . $numero_orden;
            }
        } else {
            $codigo = $mes . $anio . '1';
        }
        return $codigo;
    }
    /**
     * PDF ORDEN EXAMENES
     */
    public function imprimir_orden_pdf(){
        $empresa_id = Auth::user()->empresa_id;
        $empresa = Empresa::where('id',$empresa_id)->first();//data empresa

        $orden_id = Lucipher::Descipher(request()->input('id'));

        if($orden_id){
            $data = DB::select('SELECT o.numero_orden,o.fecha,o.hora,o.estado,c.nombre as categoria,e.nombre as examen,emp.codigo_empleado,emp.nombre as colaborador,a.departamento as area_depto,empsa.nombre as empresa FROM `orden_labs` as o INNER JOIN det_orden_labs as d on o.id=d.orden_lab_id and o.numero_orden=d.numero_orden and o.empleado_id=d.empleado_id inner join examenes as e on d.examen_id=e.id and o.empresa_id=e.empresa_id inner join categoria_examens as c on d.categoria_id=c.id and e.categoria_id=c.id and c.empresa_id=o.empresa_id INNER JOIN empleados as emp on o.empleado_id=emp.id and o.empresa_id=emp.empresa_id INNER JOIN area_departamento_emps as a on emp.area_depto_id=a.id and emp.empresa_id=a.empresa_id INNER JOIN empresas as empsa on emp.empresa_id=empsa.id WHERE o.id = ? and o.empresa_id = ?',[$orden_id,$empresa_id]);
            $data_orden = [];
            foreach($data as $row){
                $data_orden = [
                    'codigo_empleado' => $row->codigo_empleado,
                    'numero_orden' => $row->numero_orden,
                    'fecha' => date('d-m-Y',strtotime($row->fecha)),
                    'hora' => $row->hora,
                    'estado' => $row->estado,
                    'colaborador' => Lucipher::Descipher($row->colaborador),
                    'area_depto' => Lucipher::Descipher($row->area_depto),
                    'empresa' => $row->empresa,
                    'examenes' => [] // Inicializamos el array de exámenes.
                ];
                foreach($data as $item){
                    if (!empty($item->examen)) {
                        $data_orden['examenes'][] = ['examen' => $item->examen,'categoria' => $item->categoria];
                    }
                }
                break;
            }
    
            $pdf = PDF::loadView('Orden.pdf.imp_orden_examenes',compact('data_orden','empresa'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('examenes-orden-'.$data_orden['numero_orden'].".pdf");
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error inesperado.'
        ]);
    }
}

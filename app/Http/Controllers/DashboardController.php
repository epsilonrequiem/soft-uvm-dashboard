<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use DateTime;
use Exception;

class DashboardController extends Controller
{

    public function view()
    {
        return view('dashboard');
    }

    /**
     * Test connection to ciclo_activo_web database
     */
    public function pruebaCiclo() 
    {

        $codigo = 200;
        $response = [
            'estatus' => 'exito',
            'mensaje' => '',
            'datos' => []
        ];

        try {

            $data = DB::connection('mysqlciclo')->table('lp_crm_leads_cicloactivo')->limit(10)->get();

            $response['datos'] = [
                'leads' => $data
            ];
        } catch (QueryException $e) {

            $codigo = 500;
            $response['estatus'] = 'error';
            $response['mensaje'] = 'Error en la consulta: '.$e->getMessage();
        } catch (Exception $e) {
              
            $codigo = 500;
            $response['estatus'] = 'error';
            $response['mensaje'] = 'Error general: '.$e->getMessage();
        }

        return response()->json($response, $codigo);
    }

    /**
     * Get leads by date
     */
    public function leadsCiclo(Request $request) 
    {   

        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $campus = $request->input('campus');
        $programa = $request->input('programa');
        
        $tipo = 2; // por dia
        // Validamos sin las fechas son iguales
        if ($fechaInicio == $fechaFin) {
            $tipo = 1; // por hora
        }

        $codigo = 200;
        
        $response = [
            'estatus' => 'exito',
            'mensaje' => '',
            'datos' => []
        ];

        try {

            $leads = DB::connection('mysqlciclo')->table('dashboard_view')
            ->where('procesado', 1)
            ->where('nivelInteres', '!=', 'EC')
            ->where('urlreferrer', 'NOT LIKE', '%simulador%')
            ->where('urlreferrer', 'LIKE', '%uvm.mx%')        
            ->whereNotIn('banner', ['HB_REACT', 'HB-WA-REACT']);

            if ($campus != 'TODOS') {
                $leads->where('campus', '=', $campus);        
            }

            if ($programa != 'TODOS') {
                $leads->where('carrera', '=', $programa);        
            }

            $leads->whereBetween('fechaRegistro', [$fechaInicio.' 00:00:00', $fechaFin.' 23:59:59']);
            
            if ($tipo == 1) { // por hora
                 $leads->select(DB::raw('HOUR(fechaRegistro) as hora, COUNT(*) as total'))
                    ->groupBy(DB::raw('HOUR(fechaRegistro)'));
            } else { // por dia
                 $leads->select(DB::raw('DATE(fechaRegistro) as hora, COUNT(*) as total'))
                    ->groupBy(DB::raw('DATE(fechaRegistro)'));
            }

            $leads->orderBy('hora', 'ASC');

            $leads_calculadora = (clone $leads)->whereIn('landingPage', ['CALCULADORA'])->get();
            $leads_general = (clone $leads)->whereIn('landingPage', ['WEB'])->get();
            $leads_total = (clone $leads)->whereIn('landingPage', ['CALCULADORA', 'WEB'])->get();
        
            // --------------------------------------------------------------------

            $fecha_ini = new DateTime($fechaInicio);
            $fecha_ini->modify('-1 year');
            $fecha_last_inicio = $fecha_ini->format('Y-m-d');

            $fecha_fin = new DateTime($fechaFin);
            $fecha_fin->modify('-1 year');
            $fecha_last_fin = $fecha_fin->format('Y-m-d');

            $leads_last = DB::connection('mysqlciclo')->table('dashboard_view')
            ->where('procesado', 1)
            ->where('nivelInteres', '!=', 'EC')
            ->where('urlreferrer', 'NOT LIKE', '%simulador%')
            ->where('urlreferrer', 'LIKE', '%uvm.mx%')        
            ->whereNotIn('banner', ['HB_REACT', 'HB-WA-REACT']);

            if ($campus != 'TODOS') {
                $leads_last->where('campus', '=', $campus);        
            }

            if ($programa != 'TODOS') {
                $leads_last->where('carrera', '=', $programa);        
            }

            $leads_last->whereBetween('fechaRegistro', [$fecha_last_inicio.' 00:00:00', $fecha_last_fin.' 23:59:59']);

            if ($tipo == 1) { // por hora
                 $leads_last->select(DB::raw('HOUR(fechaRegistro) as hora, COUNT(*) as total'))            
                    ->groupBy(DB::raw('HOUR(fechaRegistro)'));
            } else { // por dia
                 $leads_last->select(DB::raw('DATE(fechaRegistro) as hora, COUNT(*) as total'))            
                    ->groupBy(DB::raw('DATE(fechaRegistro)'));
            }

            $leads_last->orderBy('hora', 'ASC');

            $leads_calculadora_last = (clone $leads_last)->whereIn('landingPage', ['CALCULADORA'])->get();
            $leads_general_last = (clone $leads_last)->whereIn('landingPage', ['WEB'])->get();
            $leads_total_last = (clone $leads_last)->whereIn('landingPage', ['CALCULADORA', 'WEB'])->get();
        
            // ----------------------------------------------------------------------

            $response['datos'] = [
                'leads_calculadora' => $leads_calculadora,
                'leads_general' => $leads_general,
                'leads_total' => $leads_total,
                'leads_calculadora_last' => $leads_calculadora_last,
                'leads_general_last' => $leads_general_last,
                'leads_total_last' => $leads_total_last,
            ];
        } catch (QueryException $e) {

            $codigo = 500;
            $response['estatus'] = 'error';
            $response['mensaje'] = 'Error en la consulta: '.$e->getMessage();
        } catch (Exception $e) {
              
            $codigo = 500;
            $response['estatus'] = 'error';
            $response['mensaje'] = 'Error general: '.$e->getMessage();
        }

        return response()->json($response, $codigo);
    }
}

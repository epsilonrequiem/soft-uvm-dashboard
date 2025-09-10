<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class CicloActiveController extends Controller
{
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
    public function leadsCiclo($date) 
    {   
        $codigo = 200;
        $response = [
            'estatus' => 'exito',
            'mensaje' => '',
            'datos' => []
        ];

        try {

            $leads = DB::connection('mysqlciclo')->table('lp_crm_leads_cicloactivo')
            ->where('procesado', 1)
            ->where('nivelInteres', '!=', 'EC')
            ->where('urlreferrer', 'NOT LIKE', '%simulador%')
            ->where('urlreferrer', 'LIKE', '%uvm.mx%')        
            ->whereNotIn('banner', ['HB_REACT', 'HB-WA-REACT'])
            ->whereBetween('fechaRegistro', [$date.' 00:00:00', $date.' 23:59:59'])
            ->select(DB::raw('HOUR(fechaRegistro) as hora, COUNT(*) as total'))
            ->groupBy(DB::raw('HOUR(fechaRegistro)'))
            ->orderBy('hora', 'ASC');

            $leads_calculadora = (clone $leads)->whereIn('landingPage', ['CALCULADORA'])->get();
            $leads_general = (clone $leads)->whereIn('landingPage', ['WEB'])->get();
            $leads_total = (clone $leads)->whereIn('landingPage', ['CALCULADORA', 'WEB'])->get();
        
            $response['datos'] = [
                'leads_calculadora' => $leads_calculadora,
                'leads_general' => $leads_general,
                'leads_total' => $leads_total
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

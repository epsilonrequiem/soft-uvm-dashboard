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
        $campus = json_decode($request->input('campus'), true);
        $programa = json_decode($request->input('programa'), true);
        $dominios = $request->input('dominios');
        $paginas = json_decode($request->input('paginas'), true);
        $banner = array_filter(explode(",", $request->input('banner', '')));

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
            // ->where('urlreferrer', 'LIKE', '%uvm.mx%')        
            ->whereNotIn('banner', ['HB_REACT', 'HB-WA-REACT']);

            if ($dominios != 'TODOS') {
                if (!in_array('TODOS', $paginas)) {
                    $leads->where(function ($q) use ($paginas) {
                        foreach ($paginas as $pagina) {
                            $q->orWhere('urlreferrer', '=', $pagina);
                        }
                    });        
                } else {
                    $leads->where('urlreferrer', 'LIKE', "%$dominios%");      
                }   
            } 

            if (!in_array('TODOS', $campus)) {
                $leads->whereIN('campus', $campus);        
            }

            if (!in_array('TODOS', $programa)) {
                $leads->whereIN('carrera', $programa);        
            }

            if (count($banner) >= 1) {
                $leads->whereIn('banner', $banner);
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
        
            if ($tipo == 1) { // por hora

                $horas_calculadora = [];
                $horas_general = [];
                $horas_total = [];

                for ($i = 0; $i < 24; $i++) {
                    $horas_calculadora[$i] = ['hora' => $i, 'total' => 0];
                    $horas_general[$i] = ['hora' => $i, 'total' => 0];
                    $horas_total[$i] = ['hora' => $i, 'total' => 0];
                }

                // Rellena con los datos reales
                foreach ($leads_calculadora as $item) {
                    $horas_calculadora[$item->hora]['total'] = $item->total;
                }

                foreach ($leads_general as $item) {
                    $horas_general[$item->hora]['total'] = $item->total;
                }

                foreach ($leads_total as $item) {
                    $horas_total[$item->hora]['total'] = $item->total;
                }

            } else {
                
                $horas_calculadora = $leads_calculadora;
                $horas_general = $leads_general;
                $horas_total = $leads_total;
            }

            // --------------------------------------------------------------------

            $fecha_ini = new DateTime($fechaInicio);
            $fecha_last_inicio = $this->obtenerDiaMasCercanoDelAnioAnterior($fecha_ini);

            $fecha_fin = new DateTime($fechaFin);
            $fecha_last_fin =  $this->obtenerDiaMasCercanoDelAnioAnterior($fecha_fin);

            $leads_last = DB::connection('mysqlciclo')->table('dashboard_view')
            ->where('procesado', 1)
            ->where('nivelInteres', '!=', 'EC')
            ->where('urlreferrer', 'NOT LIKE', '%simulador%')
            // ->where('urlreferrer', 'LIKE', '%uvm.mx%')        
            ->whereNotIn('banner', ['HB_REACT', 'HB-WA-REACT']);

            if ($dominios != 'TODOS') {
                if (!in_array('TODOS', $paginas)) {
                    $leads_last->where(function ($q) use ($paginas) {
                        foreach ($paginas as $pagina) {
                            $q->orWhere('urlreferrer', '=', $pagina);
                        }
                    });        
                } else {
                    $leads_last->where('urlreferrer', 'LIKE', "%$dominios%");      
                }   
            } 

            if (!in_array('TODOS', $campus)) {
                $leads_last->whereIN('campus', $campus);        
            }

            if (!in_array('TODOS', $programa)) {
                $leads_last->whereIN('carrera', $programa);        
            }

            if (count($banner) >= 1) {
                $leads_last->whereIn('banner', $banner);
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

            if ($tipo == 1) { // por hora

                $horas_calculadora_last = [];
                $horas_general_last = [];
                $horas_total_last = [];

                for ($i = 0; $i < 24; $i++) {
                    $horas_calculadora_last[$i] = ['hora' => $i, 'total' => 0];
                    $horas_general_last[$i] = ['hora' => $i, 'total' => 0];
                    $horas_total_last[$i] = ['hora' => $i, 'total' => 0];
                }

                // Rellena con los datos reales
                foreach ($leads_calculadora_last as $item) {
                    $horas_calculadora_last[$item->hora]['total'] = $item->total;
                }

                foreach ($leads_general_last as $item) {
                    $horas_general_last[$item->hora]['total'] = $item->total;
                }

                foreach ($leads_total_last as $item) {
                    $horas_total_last[$item->hora]['total'] = $item->total;
                }
            } else {
                
                $horas_calculadora_last = $leads_calculadora_last;
                $horas_general_last = $leads_general_last;
                $horas_total_last = $leads_total_last;
            }
            
            // ----------------------------------------------------------------------

            $response['datos'] = [
                'leads_calculadora' => $horas_calculadora,
                'leads_general' => $horas_general,
                'leads_total' => $horas_total,
                'leads_calculadora_last' => $horas_calculadora_last,
                'leads_general_last' => $horas_general_last,
                'leads_total_last' => $horas_total_last,
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

    // Get day last
    public function obtenerDiaMasCercanoDelAnioAnterior($fechaActual) {
        // Día de la semana actual (1=Lunes, 7=Domingo)
        $diaSemanaActual = (int)$fechaActual->format('N');

        // Fecha un año antes
        $fechaAnterior = (clone $fechaActual)->modify('-1 year');

        // Si ya coincide el día, devolverla
        if ((int)$fechaAnterior->format('N') === $diaSemanaActual) {
            return $fechaAnterior->format('Y-m-d');
        }

        // Calcular diferencia hacia atrás y hacia adelante
        $diferenciaAntes = $diaSemanaActual - (int)$fechaAnterior->format('N');
        if ($diferenciaAntes < 0) $diferenciaAntes += 7;

        $diferenciaDespues = 7 - $diferenciaAntes;

        // Ajustar al más cercano
        if ($diferenciaAntes <= $diferenciaDespues) {
            $fechaAnterior->modify("+$diferenciaAntes days");
        } else {
            $fechaAnterior->modify("-$diferenciaDespues days");
        }

        return $fechaAnterior->format('Y-m-d');
    }
}

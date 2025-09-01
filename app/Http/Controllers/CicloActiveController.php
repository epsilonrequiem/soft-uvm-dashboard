<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CicloActiveController extends Controller
{
    public function pruebaCiclo() 
    {

        $data = DB::connection('mysqlciclo')->table('lp_crm_leads_cicloactivo')->limit(10)->get();

        return response()->json(['data' => $data]);
    }
}

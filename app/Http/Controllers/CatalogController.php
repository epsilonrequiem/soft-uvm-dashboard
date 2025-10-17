<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Perfil;
use App\Models\Year;

class CatalogController extends Controller
{
    public function getPerfiles()
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $perfil = Perfil::all();
            $response['data'] = $perfil;
        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }

    public function getCampus()
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $campus = DB::connection('mysqlciclo')
            ->table('campus')
            ->select(DB::raw('crm_nombre as campus'))
            ->where('id', '!=', 21)
            ->get();

            $response['data'] = $campus;
        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }

    public function getProgramas()
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $programas = DB::connection('mysqlciclo')
            ->table('programas')
            ->select(DB::raw('nombre as carrera'))
            ->get();

            $response['data'] = $programas;
        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }

    
    public function getYears()
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $years = Year::all();

            $response['data'] = $years;
        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }

}

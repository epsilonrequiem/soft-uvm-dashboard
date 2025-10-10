<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;

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
}

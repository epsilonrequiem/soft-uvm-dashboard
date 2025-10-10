<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\User;
use Exception;

class UserController extends Controller
{
 
    public $status;
    public $message;
    public $data;
    public $code;
 
    public function create()
    {
        return view('users');
    }

    public function index()
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $users = User::with('perfil')->get();
            $response['data'] = $users;
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

    public function store(Request $request)
    {
     
        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 201
        ];

        $messages = [
            'id_perfil.required' => 'El campo perfil es obligatorio', 
        ];

        $validate = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:12', 'regex:/^(?=.*[A-Z])(?=.*\d).{8,12}$/'],
            'id_perfil' => ['required']
        ],$messages);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'datos invalidos',
                'errors' => formatedError($validate->errors()),
                'code' => 400
            ],400);
        }

        try {

            $validatedData = $validate->validated(); 

            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['status'] = 1;

            $user = User::create($validatedData);

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

    public function show($id)
    {

        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {
            
            $user = User::where('id', '=', $id)->first();
            $response['data'] = $user;
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
    
    public function update(Request $request, $id)
    {
     
        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];
        
        $messages = [
            'id_perfil.required' => 'El campo perfil es obligatorio', 
        ];

        if ($request->input('password')) {
            $validate = Validator::make($request->all(), [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($id)],
                'password' => ['required','min:8', 'max:12', 'regex:/^(?=.*[A-Z])(?=.*\d).{8,12}$/'],
                'id_perfil' => ['required']
            ], $messages);
        } else {

            $validate = Validator::make($request->all(), [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($id)],
                'id_perfil' => ['required']
            ], $messages);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'datos invalidos',
                'errors' => formatedError($validate->errors()),
                'code' => 400
            ],400);
        }

        try {

            $user_model = User::find($id);

            if (!$user_model) {
                $this->code = 404;
                throw new Exception("Usuario no registrado", 1);
            }

            $validatedData = $validate->validated(); 

            if ($request->input('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            $user_model->fill($validatedData);
            $user_model->save();

        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = $this->code ? $this->code : 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }

    public function updateEstatus($id)
    {
     
        $response = [
            'status' => 'exito',
            'message' => '',
            'data' => null,
            'code' => 200
        ];

        try {

            $user_model = User::find($id);

            if (!$user_model) {
                $this->code = 404;
                throw new Exception("Usuario no registrado", 1);
            }

            $data = [
                'status' => $user_model->status ? 0 : 1 
            ];

            $user_model->fill($data);
            $user_model->save();

        } catch (QueryException $e) {

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } catch (Exception $th) {
            
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
            $response['code'] = $this->code ? $this->code : 500;
        }

        return response()->json(['response' => $response], $response['code']);
    }
}

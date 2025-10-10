<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required' 
        ]);
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Usuario no encontrado.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login' => 'Contraseña incorrecta.']);
        }

        if ($user->status == 0) {
            // dd($user);
            return back()->withErrors(['login' => 'Acceso denegado.']);
        }

        Auth::login($user);
        session()->regenerate();
        session(['id_perfil' => $user->id_perfil]);

        return redirect('dashboard')->with('success', 'Éxito');

    }
    
    public function destroy(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate(); 
        $request->session()->regenerateToken();

        return redirect('/login')->with(['success'=> 'Sección eliminada']);
    }
}

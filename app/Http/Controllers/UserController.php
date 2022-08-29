<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function login(Request $req){
        $credentials = $req->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);
        $recordar = ($req->has('remember') ? true : false);
        if (Auth::attempt($credentials,$recordar)) {
            $req->session()->regenerate();

            return redirect()->intended(route('home'));
        }
        return redirect(route('login'))->withInput()->withErrors([
            'name' => 'Las credenciales introducidas no son correctas.'
        ]);
    }

    public function register(Request $req){
        //Validar los datos
        $validated = $req->validate([
            "email" => ["required","email","unique:App\Models\User,email","max:150"],
            "name" => ["required","unique:App\Models\User,name","max:30"],
            "password" => ["required",Password::min(6)->mixedCase()->numbers()]
        ]);

        //Generar el usuario

        $user = new User();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        try{
            //Guardarlo y autenticarlo
            $user->save();
            Auth::login($user);
        }catch(\Exception $e){
            return back()->withInput()->withErrors(["exception" => (config('app.debug') == true ? $e->getMessage() : "Se ha producido un error al registrar la solicitud.")]);
        }
        return redirect(route('home'));
    }

    public function logout(Request $req){
        Auth::logout();
 
        $req->session()->invalidate();
     
        $req->session()->regenerateToken();
     
        return redirect(route('login'));
    }
}

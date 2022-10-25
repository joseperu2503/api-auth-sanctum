<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register (Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($request->all());
        return [
            'success' => true,
            'message' => 'Usuario registrado correctamente'
        ];
    }

    public function login (Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email',$request->email)->first();
        if(isset($user)){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken("auth_token")->plainTextToken;
                return [
                    'success' => true,
                    'message' => 'Usuario logeado correctamente',
                    'access:token' => $token
                ];
            }else{
                return [
                    'success' => false,
                    'message' => 'Contraseña incorrecta'
                ];
            }
        }else{
            return [
                'success' => false,
                'message' => 'El usuario no está registrado'
            ];
        }
    }

    public function userProfile (){
        return [
            'success' => false,
            'message' => 'Acerca del usuario',
            'data' => auth()->user()
        ];
    }

    public function logout (){

        auth()->user()->tokens()->delete();
        return [
            'success' => true,
            'message' => 'Logout exitoso'
        ];
    }

    public function prueba (){
        return [
            'success' => true,
            'message' => 'Usuario registrado correctamente'
        ];
    }
}

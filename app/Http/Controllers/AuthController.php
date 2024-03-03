<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }
    
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'error' => false,
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer'
                ]
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    public function register(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('API TOKEN')->plainTextToken;

        return response()->json([
            'error' => false,
            'token' => $token,
            'message' => 'Usuario creado correctamente',
            'user' => $user
        ]);
    }

    public function logout() {
        Auth::user()->tokens()->delete();

        return response()->json([
            'error' => false,
            'message' => 'Sesión terminada correctamente'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate; // <-- ¡Aquí importamos el Gate!

class AuthController
{
    /**
     * Registra un nuevo usuario
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Creamos el token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * Inicia sesión verificando las credenciales
     */
    public function login(Request $request)
    {
        // Revisamos si el correo y la contraseña coinciden
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas. Revisa tu correo o contraseña.'
            ], 401);
        }

        // Buscamos al usuario en la base de datos
        $user = User::where('email', $request->email)->firstOrFail();

        // Le damos su token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Cierra la sesión y destruye el token actual
     */
    public function logout(Request $request)
    {
        // Borramos el token que estaba usando el usuario
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Retorna el usuario actual y sus permisos calculados
     */
    public function me(Request $request) 
    {
        $user = $request->user();
        
        return response()->json([
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'rol'      => $user->rol,
            'permisos' => [
                'crear'    => Gate::allows('crear-producto'),
                'editar'   => Gate::allows('editar-producto'),
                'eliminar' => Gate::allows('eliminar-producto'),
            ],
        ]);
    }
}
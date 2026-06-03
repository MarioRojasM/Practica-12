<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AuthController; 

// --- RUTAS DE AUTENTICACIÓN ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS (Requieren inicio de sesión) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Aquí está nuestra ruta /me apuntando a tu función
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // 2. Protegemos las acciones de administrador (crear, actualizar, borrar)
    Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);
});

// --- RUTAS PÚBLICAS (Catálogo) ---
// Las vistas de los productos se quedan públicas para que cualquiera vea el catálogo
Route::apiResource('productos', ProductoController::class)->only(['index', 'show']);

Route::apiResource('categorias', CategoriaController::class);
Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']);
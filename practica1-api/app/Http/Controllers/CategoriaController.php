<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ProductoResource;

class CategoriaController // <-- ¡Aquí le quitamos el "extends Controller"!
{
    // Método index con eager loading
    public function index() 
    {
        return CategoriaResource::collection(
            Categoria::with('productos')->get()
        );
    }

    // Método productos para filtrar por categoría
    public function productos(Categoria $categoria) 
    {
        return ProductoResource::collection(
            $categoria->productos()->with('categoria')->get()
        );
    }
}
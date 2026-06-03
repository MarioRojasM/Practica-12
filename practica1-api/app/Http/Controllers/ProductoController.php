<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;

class ProductoController
{
    // Carga los productos usando Filtros y Paginación
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'), $request->get('dir', 'asc'))
            ->paginate($request->get('por_pagina', 15));

        // Usamos la colección del Resource para que incluya los metadatos de paginación
        return ProductoResource::collection($productos);
    }

    // Guarda uno nuevo
    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    // EL NUEVO PODER DESTRUIR
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        
        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
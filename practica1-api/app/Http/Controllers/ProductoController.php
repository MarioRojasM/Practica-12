<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- Importamos
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController
{
    use AuthorizesRequests; // <-- Lo activamos aquí adentro

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
    public function store(StoreProductoRequest $request) // <-- CAMBIO AQUÍ
    {
        //  Solo Admin o Editor pasan de aquí
        $this->authorize('create', Producto::class);
        
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    // EL NUEVO PODER DESTRUIR
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // ¡El escudo de seguridad para borrar! Solo Admin pasa de aquí
        $this->authorize('delete', $producto);
        
        $producto->delete();
        
        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
    public function update(UpdateProductoRequest $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        //  Solo Admin o Editor pasan de aquí
        $this->authorize('update', $producto);
        
        // Si la validación y los permisos pasan, actualizamos
        $producto->update($request->all());
        
        return response()->json($producto);
    }
}
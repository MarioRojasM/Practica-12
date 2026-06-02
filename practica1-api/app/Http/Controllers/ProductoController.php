<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController
{
    // Carga los productos
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return response()->json($productos);
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
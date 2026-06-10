<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Jobs\EnviarConfirmacionPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller; // ✅ Arreglo del Error de Controlador

class PedidoController extends Controller
{
    public function store(Request $request) 
    {
        $pedido = DB::transaction(function () use ($request) {
            // 1. Crear el pedido calculando el total
            $p = Pedido::create([
                'user_id' => auth()->id(),
                'total'   => collect($request->items)->sum(fn($i) => $i['precio'] * $i['cantidad']),
            ]);

            // 2. Guardar los items y descontar el stock
            foreach ($request->items as $item) {
                
                // ✅ Arreglo para que la base de datos guarde bien el precio_unitario
                $p->items()->create([
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'], 
                ]);
                
                Producto::find($item['producto_id'])->decrement('stock', $item['cantidad']);
            }
            
            return $p;
        });

        // 3. Despachamos el Job a la cola con los 5 segundos
        EnviarConfirmacionPedido::dispatch($pedido)->delay(now()->addSeconds(5));

        return response()->json(['pedido_id' => $pedido->id], 201);
    }

    // 4. Vue consulta esto cada 3 segundos para ver si ya llegó el correo
    public function show($id)
    {
        return Pedido::findOrFail($id);
    }
}
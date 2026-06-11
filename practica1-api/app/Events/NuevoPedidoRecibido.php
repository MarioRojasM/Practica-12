<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevoPedidoRecibido implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels; // [cite: 36, 37]

    public function __construct(public Pedido $pedido) {} // [cite: 38]

    // Canal privado: solo admins autenticados [cite: 39]
    public function broadcastOn(): array {
        return [new PrivateChannel('admin-panel')]; // [cite: 40, 41, 42]
    }

    // Datos que se envían al frontend [cite: 43]
    public function broadcastWith(): array {
        return [ // [cite: 44, 45]
            'id'         => $this->pedido->id, // [cite: 46]
            'total'      => $this->pedido->total, // [cite: 47]
            'cliente'    => $this->pedido->user->name, // [cite: 48]
            'items'      => $this->pedido->items->count(), // [cite: 49]
            'created_at' => $this->pedido->created_at->format('H:i:s'), // [cite: 50]
        ]; // [cite: 51, 52]
    }
} // [cite: 53]

<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Notifications\ConfirmacionPedidoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class EnviarConfirmacionPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;           // 3 reintentos si falla [cite: 68]
    public int $timeout = 60;        // 60 segundos máximos [cite: 69]

    public function __construct(public Pedido $pedido) {} // [cite: 70]

    public function handle(): void
    {
        // Enviar la notificación al usuario [cite: 72]
        $this->pedido->user->notify(
            new ConfirmacionPedidoNotification($this->pedido) // [cite: 73-74]
        );

        // Marcar como enviado en la base de datos [cite: 76]
        $this->pedido->update([
            'email_enviado_at' => now()->toDateTimeString() // [cite: 77-78]
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('Fallo envío email pedido ' . $this->pedido->id); // [cite: 81-82]
    }
}
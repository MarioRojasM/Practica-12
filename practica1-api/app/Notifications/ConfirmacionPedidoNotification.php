<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmacionPedidoNotification extends Notification
{
    use Queueable;

    // Recibimos el pedido para poder usar sus datos en el correo
    public function __construct(public Pedido $pedido)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail']; // [cite: 87-89]
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmación de tu pedido #' . $this->pedido->id) // [cite: 92]
            ->greeting('¡Hola, ' . $notifiable->name . '!') // [cite: 93]
            ->line('Recibimos tu pedido correctamente.') // [cite: 94]
            ->line('Total: $' . number_format($this->pedido->total, 2)) // [cite: 95]
            ->action('Ver mi pedido', url('/pedidos/' . $this->pedido->id)) // [cite: 96]
            ->line('Gracias por tu compra.'); // [cite: 97-98]
    }
}
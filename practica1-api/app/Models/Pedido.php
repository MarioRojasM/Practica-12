<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    // ¡AQUÍ ESTÁ LA MAGIA! Le damos permiso a Laravel de guardar estos datos
    protected $fillable = ['user_id', 'total', 'estado', 'email_enviado_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
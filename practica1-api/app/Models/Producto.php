<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Aquí le damos permiso a Laravel de guardar estos campos
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id', // ¡Este es el más importante!
        'imagen'
    ];

    // Le decimos cómo conectarse con la tabla de Categorías
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
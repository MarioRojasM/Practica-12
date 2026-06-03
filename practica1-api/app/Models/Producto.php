<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',
        'imagen'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // --- AQUÍ EMPIEZAN LOS QUERY SCOPES ---

    // Filtra buscando coincidencias en nombre o descripción
    public function scopeBuscar($query, $termino) {
        return $query->where('nombre', 'LIKE', "%{$termino}%")
                     ->orWhere('descripcion', 'LIKE', "%{$termino}%");
    }

    // Filtra por una categoría en específico
    public function scopeDeCategoria($query, $categoriaId) {
        return $query->when($categoriaId,
            fn($q) => $q->where('categoria_id', $categoriaId)
        );
    }

    // Filtra entre un precio mínimo y un máximo
    public function scopeRangoPrecio($query, $min, $max) {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max));
    }
}
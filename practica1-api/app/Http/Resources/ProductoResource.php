<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoriaResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio'      => $this->precio,
            'stock'       => $this->stock,
            // Aquí enlazamos la categoría
            'categoria'   => new CategoriaResource($this->whenLoaded('categoria')),
            'imagen_url'  => $this->imagen ? asset('storage/' . $this->imagen) : null,
            'created_at'  => $this->created_at,
        ];
    }
}
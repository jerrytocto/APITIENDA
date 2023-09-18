<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'name',
        'description',
        'price',
        'quantity_available',
        'categoria_id',
        'marca_id'
    ];

    //Un producto pertenece a una categoría 
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    //Un producto pernetence a una marca 
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    //Un producto puede estar en varias compras 
    public function compras()
    {
        return $this->belongsToMany(Compra::class)
                    ->withPivot('price','quantity','subtotal') // Son la columnas adicionales que tiene la tabla intermedia 
                    ->withTimestamps();
    }
}

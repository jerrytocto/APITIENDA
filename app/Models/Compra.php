<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [ 'subtotal','total'];

    //A una compra le pueden pertenecer varios productos 
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
                    ->withPivot('price','quantity','subtotal')// Son la columnas adicionales que tiene la tabla intermedia 
                    ->withTimestamps();
    }
}

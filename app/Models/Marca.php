<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['name','description'];

    //Una marca puede tener varios productos 
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}

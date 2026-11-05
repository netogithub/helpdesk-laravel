<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

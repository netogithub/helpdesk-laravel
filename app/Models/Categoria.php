<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre', 'descripcion'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

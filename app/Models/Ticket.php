<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'estado', 'prioridad',
        'categoria_id', 'solicitante_id', 'tecnico_id', 'cerrado_en',
    ];

    protected $casts = ['cerrado_en' => 'datetime'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstado::class);
    }
}

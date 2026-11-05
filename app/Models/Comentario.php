<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'contenido'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

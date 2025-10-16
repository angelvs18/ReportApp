<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tarea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     */
   protected $fillable = [
    'folio', // Cambiado de 'titulo'
    'descripcion',
    'actividades', // Nuevo
    'observaciones', // Nuevo
    'tipo',
    'estado',
    'user_id',
];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function generadorDetalle(): HasOne
{
    return $this->hasOne(GeneradorDetalle::class);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'coordenadas_gps',
        'tipo_reporte',
        'user_id',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
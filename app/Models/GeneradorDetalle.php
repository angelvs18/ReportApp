<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneradorDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'numeros_economicos',
    ];

    // Le decimos a Laravel que este campo es un array
    protected $casts = [
        'numeros_economicos' => 'array',
    ];

    // Relación inversa: Un detalle pertenece a una tarea
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }
}
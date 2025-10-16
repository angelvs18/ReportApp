<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'gps_marca',
        'gps_modelo',
        'gps_imei',
        'vehiculo_marca',
        'vehiculo_modelo',
        'vehiculo_matricula',
        'vehiculo_numero_economico',
    ];

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }
}
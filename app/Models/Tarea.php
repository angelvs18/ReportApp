<?php

namespace App\Models;

use App\Models\GeneradorDetalle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\VehiculoDetalle;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'descripcion',
        'actividades',
        'observaciones',
        'tipo',
        'estado',
        'user_id', 
        'instalador_nombre',
        'instalador_firma_path',
        'cliente_nombre',
        'cliente_firma_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generadorDetalle(): HasOne
    {
        return $this->hasOne(GeneradorDetalle::class);
    }
    public function vehiculoDetalle(): HasOne
{
    return $this->hasOne(VehiculoDetalle::class);
}
public function fotos(): HasMany
{
    return $this->hasMany(Foto::class);
}
}
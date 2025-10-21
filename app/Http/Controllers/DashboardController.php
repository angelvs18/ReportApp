<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $tareasPendientes = Tarea::where('estado', 'pendiente')->count();
    $tareasProceso = Tarea::where('estado', 'en_proceso')->count();
    $tareasRealizadas = Tarea::where('estado', 'completada')->count();

    $actividades = Tarea::latest()->take(5)->get();

    return view('dashboard', compact(
        'tareasPendientes',
        'tareasProceso',
        'tareasRealizadas',
        'actividades'
    ));
}
}

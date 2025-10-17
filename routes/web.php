<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tareas', TareaController::class);
});

Route::patch('/tareas/{tarea}/status', [TareaController::class, 'updateStatus'])
    ->middleware(['auth'])
    ->name('tareas.updateStatus');
    Route::get('/tareas/{tarea}/pdf', [TareaController::class, 'downloadPDF'])
    ->middleware(['auth'])
    ->name('tareas.pdf');

    Route::patch('/tareas/{tarea}/status', /* ... */)->name('tareas.updateStatus'); // Tu ruta de status
    Route::resource('tareas', TareaController::class)->middleware(['auth']); // Tu ruta resource

Route::resource('tareas', TareaController::class)->middleware(['auth', 'verified']);
require __DIR__.'/auth.php';

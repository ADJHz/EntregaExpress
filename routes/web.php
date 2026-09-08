<?php

use App\Http\Controllers\ElementosEntregaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ElementosEntregaController::class, 'index'])->name('dashboard');
Route::get('/api/entregas/pendientes', [ElementosEntregaController::class, 'pending'])->name('entregas.pendientes');
Route::get('/api/entregas/recibidos', [ElementosEntregaController::class, 'received'])->name('entregas.recibidos');
Route::post('/entregas/{elemento}/recibir', [ElementosEntregaController::class, 'receive'])
    ->name('entregas.recibir');
Route::get('/entregas/{elemento}/acuse', [ElementosEntregaController::class, 'reprint'])
    ->name('entregas.reimprimir');
Route::get('/reportes/entregas.csv', [ElementosEntregaController::class, 'report'])
    ->name('reportes.entregas');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ComentarioController;

Route::redirect('/', '/tickets');
Route::resource('tickets', TicketController::class)->except(['destroy']);
Route::post('tickets/{ticket}/estado', [TicketController::class, 'cambiarEstado'])->name('tickets.estado');
Route::post('tickets/{ticket}/tecnico', [TicketController::class, 'asignarTecnico'])->name('tickets.tecnico');
Route::post('tickets/{ticket}/comentarios', [ComentarioController::class, 'store'])->name('tickets.comentarios.store');
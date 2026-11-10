<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::redirect('/', '/tickets');
Route::resource('tickets', TicketController::class)->except(['destroy']);

<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuoteController::class, 'create']);
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');

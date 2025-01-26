<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/translation', App\Http\Controllers\TranslationController::class);
Route::post('/translation/provider/{providerName}', App\Http\Controllers\TranslationWithProviderController::class);

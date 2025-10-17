<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/facebook/webhook', App\Http\Controllers\FacebookWebhook::class);

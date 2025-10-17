<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::any('/facebook/webhook', App\Http\Controllers\FacebookWebhook::class);

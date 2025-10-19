<?php

use Illuminate\Support\Facades\Route;
use App\Models\Webhook;

Route::get('/', function () {
    $webhookCount = Webhook::count();
    return view('welcome', compact('webhookCount'));
});

Route::any('/facebook/webhook', App\Http\Controllers\FacebookWebhook::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::any('/facebook/webhook', App\Http\Controllers\FacebookWebhook::class);

// Webhook CRUD API
Route::apiResource('webhooks', App\Http\Controllers\Api\WebhookController::class);

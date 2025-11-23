<?php

use Illuminate\Support\Facades\Route;
use App\Models\Webhook;

Route::get('/', function () {
    $webhookCount = Webhook::count();
    return view('welcome', compact('webhookCount'));
});

Route::get('privacy-policy', function () {
    return view('privacy');
})->name('privacy');


Route::get('terms-of-service', function () {
    return view('terms');
})->name('terms-of-service');



Route::any('/facebook/webhook', App\Http\Controllers\WhatsAppWebhookController::class);

// Minimal transaction verify route (used in QR)
Route::get('/transactions/{id}', function (string $id) {
    return response()->json(['id' => $id]);
})->name('transaction.show');

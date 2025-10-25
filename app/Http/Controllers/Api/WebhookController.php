<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    /**
     * Display a listing of the webhooks.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 25);
        $data = Webhook::orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json($data);
    }

    /**
     * Store a newly created webhook in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request_payload' => ['sometimes'],
            'response_payload' => ['sometimes'],
            'method' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'max:50'],
            'ip_address' => ['nullable', 'string', 'max:45'],
        ]);

        $webhook = Webhook::create($validated + [
            // If request_payload isn't passed explicitly, capture raw input
            'request_payload' => $validated['request_payload'] ?? $request->all(),
        ]);

        return response()->json($webhook, 201);
    }

    /**
     * Display the specified webhook.
     */
    public function show($id): JsonResponse
    {
        $webhook = Webhook::findOrFail($id);
        return response()->json($webhook);
    }

    /**
     * Update the specified webhook in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $webhook = Webhook::findOrFail($id);

        $validated = $request->validate([
            'request_payload' => ['sometimes'],
            'response_payload' => ['sometimes'],
            'method' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'max:50'],
            'ip_address' => ['nullable', 'string', 'max:45'],
        ]);

        $webhook->update($validated);

        return response()->json($webhook);
    }

    /**
     * Remove the specified webhook from storage.
     */
    public function destroy($id): JsonResponse
    {
        $webhook = Webhook::findOrFail($id);
        $webhook->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SystemConfig;
use App\Models\Webhook;
use Illuminate\Http\Request;

class FacebookWebhook extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {


        // Handle verification requests (GET) from Facebook
        if ($request->isMethod('get')) {
            // Facebook may send hub.verify_token or hub_verify_token depending on source
            $verifyToken = $request->input('hub.verify_token') ?? $request->input('hub_verify_token');
            $challenge = $request->input('hub.challenge') ?? $request->input('hub_challenge');

            // Get expected token from SystemConfig or .env fallback
            $expected = SystemConfig::getValue('facebook_verify_token') ?? 'MyTokenIsHere';

            if ($verifyToken && $expected && hash_equals((string) $expected, (string) $verifyToken)) {
                return response($challenge, 200);
            }

            return response('Forbidden', 403);
        }

        // For non-verification requests, persist the webhook payload
        Webhook::create([
            'request_payload' => $request->all(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'status' => 'received',
        ]);

        // Ensure any other startup side-effects can still use SystemConfig
        SystemConfig::getValue('facebook_app_id');

    }
}

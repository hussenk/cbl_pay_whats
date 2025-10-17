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

        Webhook::create([
            'request_payload' => $request->all(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'status' => 'received',
        ]);

        SystemConfig::getValue('facebook_app_id');

    }
}

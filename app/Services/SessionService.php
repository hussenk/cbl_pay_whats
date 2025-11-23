<?php

namespace App\Services;

use App\Models\WhatsappSession;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class SessionService
{
    public function getOrCreate(string $phone): WhatsappSession
    {
        $session = WhatsappSession::firstOrCreate(
            ['phone_number' => $phone],
            ['current_step' => null, 'temp_data' => [], 'expires_at' => now()->addMinutes(30)]
        );

        // if expired, reset
        if ($session->expires_at && $session->expires_at->isPast()) {
            $session->current_step = null;
            $session->temp_data = [];
        }

        // keep-alive
        $session->expires_at = Carbon::now()->addMinutes(30);
        $session->save();

        return $session;
    }

    public function setStep(WhatsappSession $session, ?string $step): WhatsappSession
    {
        $session->current_step = $step;
        $session->save();
        return $session;
    }

    public function putTemp(WhatsappSession $session, string $key, mixed $value): WhatsappSession
    {
        $data = $session->temp_data ?? [];
        Arr::set($data, $key, $value);
        $session->temp_data = $data;
        $session->save();
        return $session;
    }

    public function getTemp(WhatsappSession $session, ?string $key = null, mixed $default = null): mixed
    {
        $data = $session->temp_data ?? [];
        if ($key === null) {
            return $data;
        }
        return Arr::get($data, $key, $default);
    }

    public function clear(WhatsappSession $session): void
    {
        $session->current_step = null;
        $session->temp_data = [];
        $session->save();
    }
}



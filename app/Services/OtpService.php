<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Str;

class OtpService
{
    public function generate(string $phone, int $ttlMinutes = 5): OtpCode
    {
        $code = (string) random_int(100000, 999999);

        return OtpCode::create([
            'phone_number' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);
    }

    public function validate(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone_number', $phone)
            ->where('code', $code)
            ->latest()
            ->first();

        if (!$otp) {
            return false;
        }

        if ($otp->used_at !== null) {
            return false;
        }

        if ($otp->expires_at->isPast()) {
            return false;
        }

        $otp->used_at = now();
        $otp->save();

        return true;
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'request_payload',
        'response_payload',
        'method',
        'status',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * Casting `request_payload` and `response_payload` to `array` ensures they
     * are JSON-encoded when saving and decoded when retrieving. This prevents
     * passing raw PHP arrays directly to the query grammar, which caused the
     * TypeError described.
     *
     * @var array
     */
    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];
}

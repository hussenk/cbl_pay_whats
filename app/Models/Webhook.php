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
}

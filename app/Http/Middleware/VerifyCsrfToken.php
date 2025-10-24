<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

class VerifyCsrfToken extends ValidateCsrfToken
{
    protected $except = [
        'api/*', // ignore all API routes
        'webhook/*',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => base64_decode($value),
            set: fn ($value) => base64_encode($value),
        );
    }

    public static function getValue($key)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : null;
    }
}

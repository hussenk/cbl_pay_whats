<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemConfig::create([
            'key' => 'facebook_app_id',
            'value' => '1472185290505642',
        ]);

        SystemConfig::create([
            'key' => 'facebook_token',
            'value' => 'your_facebook_token_here',
        ]);

        SystemConfig::create([
            'key' => 'facebook_secret',
            'value' => 'ca2830f93429ddef49eb8c5c0ff02cd7',
        ]);

        SystemConfig::create([
            'key' => 'whatsapp_business_number_id',
            'value' => 'your_whatsapp_business_number_id_here',
        ]);

        SystemConfig::create([
            'key' => 'whatsapp_business_phone_number',
            'value' => 'your_whatsapp_business_phone_number_here',
        ]);

        SystemConfig::create([
            'key' => 'whatsapp_token',
            'value' => 'your_whatsapp_token_here',
        ]);

        SystemConfig::create([
            'key' => 'whatsapp_secret',
            'value' => 'your_whatsapp_secret_here',
        ]);
    }
}

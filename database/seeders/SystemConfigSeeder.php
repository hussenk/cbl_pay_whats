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
            'value' => 'your_facebook_app_id_here',
        ]);

        SystemConfig::create([
            'key' => 'facebook_token',
            'value' => 'your_facebook_token_here',
        ]);

        SystemConfig::create([
            'key' => 'facebook_secret',
            'value' => 'your_facebook_secret_here',
        ]);

        
    }
}

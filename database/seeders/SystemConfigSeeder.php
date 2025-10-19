<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
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
        SystemConfig::updateOrCreate([
            'key' => 'facebook_app_id',
        ], [
            'value' => '1472185290505642',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'facebook_token',
        ], [
            'value' => 'EAAU68dlIvaoBPieIAkwxAaXTjZC572IwP16wxsvQv6HdmZBLdOmAK8wpfB0bZAGcJsHYer8rrgWCinqkP34rLCwh5NByZAekslqDKMHYhGR7h4Wouqoj0dPoeZCKZAtZCIisKTRXdLp0UTbO9cGMSM94bc1OXdsCluP3nqAKZCO3TR1xJoYHBdaHlwo20LOecEb70KSZCcZA9TfNByfDywEGFQxqN9bgq2laUzEGydbZAePXq9K6wZDZD',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'facebook_secret',
        ], [
            'value' => 'ca2830f93429ddef49eb8c5c0ff02cd7',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_business_number_id',
        ], [
            'value' => 'your_whatsapp_business_number_id_here',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_business_phone_number',
        ], [
            'value' => 'your_whatsapp_business_phone_number_here',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_token',
        ], [
            'value' => 'EAAU68dlIvaoBPieIAkwxAaXTjZC572IwP16wxsvQv6HdmZBLdOmAK8wpfB0bZAGcJsHYer8rrgWCinqkP34rLCwh5NByZAekslqDKMHYhGR7h4Wouqoj0dPoeZCKZAtZCIisKTRXdLp0UTbO9cGMSM94bc1OXdsCluP3nqAKZCO3TR1xJoYHBdaHlwo20LOecEb70KSZCcZA9TfNByfDywEGFQxqN9bgq2laUzEGydbZAePXq9K6wZDZD',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_secret',
        ], [
            'value' => 'your_whatsapp_secret_here',
        ]);
    }
}

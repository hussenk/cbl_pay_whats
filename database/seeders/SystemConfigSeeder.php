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
            'value' => '628783390202400',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'facebook_token',
        ], [
            'value' => 'EAAU68dlIvaoBPieIAkwxAaXTjZC572IwP16wxsvQv6HdmZBLdOmAK8wpfB0bZAGcJsHYer8rrgWCinqkP34rLCwh5NByZAekslqDKMHYhGR7h4Wouqoj0dPoeZCKZAtZCIisKTRXdLp0UTbO9cGMSM94bc1OXdsCluP3nqAKZCO3TR1xJoYHBdaHlwo20LOecEb70KSZCcZA9TfNByfDywEGFQxqN9bgq2laUzEGydbZAePXq9K6wZDZD',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'facebook_secret',
        ], [
            'value' => '3f47f70a6d67e4860d32eae980fa16ab',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_business_number_id',
        ], [
            'value' => '1122355860051986',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_business_phone_number',
        ], [
            'value' => '789379777599940',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_token',
        ], [
            'value' => 'EAAI74AqdGiABP41H00RBeHzko9oBFs1qDt69GwgToobX97iOhZABCRWchwV7Rzt01SvijWJsMPet1fNFSqbEjzCJQvzUwjFvZBvlvSseaRch0zDPBzDsTGtCUZBBHxiiBqQvDf24X7pb6vaC1sHUbZCrAl3MhCByQbPgss1EaURtmn70W0x9ATSevFKd3RBnRdA6OIZA13ZAiL1JNksZC2MPiegdhE3ZAIdwMj4ZB04ZCjyguE9datRIUO5wEBXgPUZBgZDZD',
        ]);

        SystemConfig::updateOrCreate([
            'key' => 'whatsapp_secret',
        ], [
            'value' => 'your_whatsapp_secret_here',
        ]);
    }
}

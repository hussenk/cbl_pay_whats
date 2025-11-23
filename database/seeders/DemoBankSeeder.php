<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoBankSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::firstOrCreate(
            ['email' => 'alice@example.com'],
            ['name' => 'Alice']
        );

        $bob = User::firstOrCreate(
            ['email' => 'bob@example.com'],
            ['name' => 'Bob']
        );

        Account::firstOrCreate([
            'number' => '0011583013001',
        ], [
            'user_id' => $alice->id,
            'name' => 'حساب عليس',
            'balance' => 10000,
            'currency' => 'USD',
        ]);

        Account::firstOrCreate([
            'number' => '0011583015001',
        ], [
            'user_id' => $bob->id,
            'name' => 'حساب بوب',
            'balance' => 5000,
            'currency' => 'USD',
        ]);
    }
}



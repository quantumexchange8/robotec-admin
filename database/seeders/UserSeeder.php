<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' =>  Hash::make('testtest'),
            'remember_token' => Str::random(10),
            'dial_code' => '+60',
            'phone' => '+60123456789',
            'referral_code' => 'RBTx666666',
            'id_number' => '000001',
            'role' => 'admin',
        ]);
    }
}

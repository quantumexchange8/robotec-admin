<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingWalletAddress;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'name' => 'pamm-return',
            'slug' => 'pamm-return',
            'value' => 0,
        ]);

        SettingWalletAddress::create([
            'wallet_address' => 'TGqosdkka9VcHB7jT6atakNyoABV2VSQkZ'
        ]);

        SettingWalletAddress::create([
            'wallet_address' => 'TKWnFU8WEeWorhp5RPtwVX6xUmteNN1QJc'
        ]);

        SettingWalletAddress::create([
            'wallet_address' => 'TFC2agUu3Du5ig8cHJSdnQ2ZZvhctPEa6J'
        ]);

        SettingWalletAddress::create([
            'wallet_address' => 'TNcystbiYUv1yy8iJ3xs7Q9WoANQ8rJvTz'
        ]);
    }
}

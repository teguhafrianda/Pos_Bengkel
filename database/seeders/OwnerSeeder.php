<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Owner Bengkel',
            'email' => 'owner@bengkel.com',
            'password' => Hash::make('password123'),
            'shop_name' => 'Bengkel Maju Motor',
            'role' => 'Owner'
        ]);
    }
}
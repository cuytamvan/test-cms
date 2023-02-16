<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        User::insert([
            'name' => 'Cuytamvan',
            'email' => 'admin@example.local',
            'password' => Hash::make('admin@example.local'),
            'role_id' => 1,
            'created_at' => $now,
        ]);
    }
}

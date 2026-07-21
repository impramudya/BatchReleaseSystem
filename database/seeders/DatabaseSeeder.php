<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin BatchRelease',
            'email' => 'admin@batchrelease.local',
            'password' => 'password123',
            'role' => 'Admin',
        ]);
    }
}

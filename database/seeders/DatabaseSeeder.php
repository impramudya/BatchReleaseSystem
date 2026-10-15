<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'SuperPram',
            'email' => 'pram@batchrelease.local',
            'password' => 'password123',
            'role' => 'Super Admin',
        ]);

        User::create([
            'name' => 'Admin BatchRelease',
            'email' => 'admin@batchrelease.local',
            'password' => 'password123',
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'Spv BatchRelease',
            'email' => 'spv@batchrelease.local',
            'password' => 'password123',
            'role' => 'Supervisor',
        ]);

        User::create([
            'name' => 'Manager BatchRelease',
            'email' => 'mgr@batchrelease.local',
            'password' => 'password123',
            'role' => 'Manager',
        ]);
    }
}

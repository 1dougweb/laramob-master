<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@laramob.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create person record for admin
        Person::create([
            'name' => 'Admin User',
            'email' => 'admin@laramob.com',
            'user_id' => $user->id,
            'type' => 'employee',
        ]);
    }
}

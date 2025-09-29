<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

     User::factory()->create([
            'firstname' => 'Test User',
            'lastname' => 'T',
            'middlename' =>'A',
            'email' => 'sp@gmail.com',
            'password' => 'password',
            'is_admin' => 2,
        ]);
    }
}

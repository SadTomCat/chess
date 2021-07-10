<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->createOne([
             'name' => 'admin',
             'email' => env('DEFAULT_ADMIN_EMAIL'),
             'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD')),
             'role' => 'admin',
         ]);
    }
}

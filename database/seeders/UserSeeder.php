<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $faker = Factory::create();
        User::factory(30)->create();
        User::insert([
            [
              'name' => 'super admin',
              'email' => 'super_admin@app.com',
              'email_verified_at' => now(),
              'password' => bcrypt('12345678'), 
              'role' => 'super_admin',
            ],
            [
              'name' => 'vocacy admin',
              'email' => 'vocacy@app.com',
              'email_verified_at' => now(),
              'password' => bcrypt('Vv_*12345678'),
              'role' => 'super_admin',
            ]
          ]);
    }
}

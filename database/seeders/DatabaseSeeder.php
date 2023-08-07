<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SeoSeeder::class,
            TeamSeeder::class,
            UserSeeder::class,
            LevelSeeder::class,
            InformationSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seo::updateOrCreate([
            'id' => 1,
            'title' => 'Nortech Games',
            'description' => 'Nortech Games',
            'keywords' => 'Nortech, Games',
            'image' => ''
        ]);
    }
}

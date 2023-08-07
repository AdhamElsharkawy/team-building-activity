<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Evaluation;
use App\Models\Criteria;
use App\Models\Team;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 4 levels
        $levels = Level::factory()
            ->count(4)
            ->create();

        $team_ids = Team::select('id')->get()->pluck('id')->toArray();
        // Create 4 evaluations for each level 
        foreach ($levels as $level) {
            Evaluation::factory()
                ->count(4)
                ->for($level)
                ->create();

            // attach all teams to each level
            foreach($team_ids as $team_id) {
                $level->teams()->attach($team_id);
            }
        }
        // Create 4 criteria for each evaluation
        foreach ($levels as $level) {
            foreach ($level->evaluations as $evaluation) {
                Criteria::factory()
                    ->count(4)
                    ->for($evaluation)
                    ->create();
            }
        }

        // create 4 levels with score
        $levels = Level::factory()
            ->count(4)
            ->create();

        // add fake score to each level
        foreach ($levels as $level) {
            foreach($team_ids as $team_id) {
                $level->teams()->attach($team_id, ['score' => rand(1, 100)]);
            }
        }
    }
}

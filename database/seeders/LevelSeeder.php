<?php

namespace Database\Seeders;

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
        // Create 8 levels
        $levels = Level::factory()
            ->count(8)
            ->create();

        $team_ids = Team::select('id')->get()->pluck('id')->toArray();
        // Create 4 evaluations for 4 level 
        for ($i = 0; $i < 4; $i++) {
            $evaluations = Evaluation::factory()
                ->count(4)
                ->create([
                    'level_id' => $levels[$i]->id,
                ]);
            // Create 4 criteria for 4 evaluation
            foreach ($evaluations as $evaluation) {
                $criteria = Criteria::factory()
                    ->count(4)
                    ->create([
                        'evaluation_id' => $evaluation->id,
                    ]);
                // attach teams to criteria
                foreach ($criteria as $criterion) {
                    $criterion->teams()->attach($team_ids);
                }
            }
        }

        // attach teams to levels
        foreach ($levels as $level) {
            $level->teams()->attach($team_ids);
        }
    }
}

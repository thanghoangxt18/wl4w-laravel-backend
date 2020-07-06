<?php

use Illuminate\Database\Seeder;

class Exercise_ZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\ExerciseZone::class, 30)->create();

    }
}

<?php

use Illuminate\Database\Seeder;

class Exercise_GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\ExerciseGroup::class, 30)->create();
    }
}

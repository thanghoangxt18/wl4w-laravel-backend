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
        factory(App\Models\Exercise_Group::class, 30)->create();
    }
}

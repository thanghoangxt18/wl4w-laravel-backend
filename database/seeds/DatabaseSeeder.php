<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('GroupsTableSeeder');
        $this->call('ExerciseTableSeeder');
        $this->call('Exercise_GroupTableSeeder');
        $this->call('ZonesTableSeeder');
        $this->call('Exercise_ZoneTableSeeder');
        $this->call('CoursesTableSeeder');
        $this->call('ItemsTableSeeder');
        $this->call('Group_ItemTableSeeder');
          $this->call('HistoriesTableSeeder');
    }
}

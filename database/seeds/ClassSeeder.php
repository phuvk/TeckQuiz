<?php

use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classes')->insert([[
            'class_id' => 1,
            'instructor_id' => 1,
            'subject_id' => 1,
            'class_active' => false
        ],[
            'class_id' => 2,
            'instructor_id' => 1,
            'subject_id' => 2,
            'class_active' => true
        ]]);
    }
}
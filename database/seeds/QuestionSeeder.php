<?php

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([[
            'question_id' => 1,
            'question_name' => 'What is 1 + 1?',
            'question_type' => 1,
            'choices' => '2;11;Both the indicated numbers;Not listed'
        ],[
            'question_id' => 2,
            'question_name' => 'Do you love her?',
            'question_type' => 2,
            'choices' => 2
        ]]);
    }
}

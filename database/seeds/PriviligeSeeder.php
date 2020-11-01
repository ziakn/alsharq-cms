<?php

use Illuminate\Database\Seeder;

class PriviligeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priviliges')->insert(
            [
            [
                'user_id' => 1,
                'pages' => 1,
                'setting' =>1,
            ],
            [
                'user_id' => 2,
                'pages' => 0,
                'setting' =>0,
            ]
            ]
        );
    }
}

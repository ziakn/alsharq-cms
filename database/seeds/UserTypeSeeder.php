<?php

use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert(
            [
            [
                'user_id' => 1,
                'name' => 'Admin',
                'status' =>1,
            ],
            [
                'user_id' => 1,
                'name' => 'Moderator',
                'status' =>1,
            ],
            ]
        );
    }
}

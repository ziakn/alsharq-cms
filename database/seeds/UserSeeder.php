<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
            [
                'name' => 'Mohamed Mosaed ',
                'email' => 'mosaed@alsharq.com',
                'password' =>bcrypt('asdasdasd'),
                'mobile' =>'12345678',
                'address' =>'Doha Qatar',
                'type' =>1,
            ],
            [
                'name' => 'moderator',
                'email' => 'moderator@alsharq.com',
                'password' =>bcrypt('asdasdasd'),
                'mobile' =>'12345678',
                'address' =>'Doha Qatar',
                'type' =>2,
            ]
            ]
        );
    }
}

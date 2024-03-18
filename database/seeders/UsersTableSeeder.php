<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define user data
        $users = [];
        for ($i = 1; $i <= 9; $i++) {
            $users[] = [
                'name' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('12345678'),
                'is_bot' => ($i <= 9) ? true : false,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert users into the database
        DB::table('users')->insert($users);
    }
}

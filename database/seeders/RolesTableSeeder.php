<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Mafia',
                'description' => 'The antagonists of the game. Each night, they vote to eliminate one player.',
            ],
            [
                'name' => 'Villager',
                'description' => 'The innocent civilians. During the day, they vote to eliminate one player.',
            ],
            [
                'name' => 'Doctor',
                'description' => 'A townsperson with healing abilities. Each night, they can choose a player to save from being killed.',
            ],
            [
                'name' => 'Mayor',
                'description' => 'A townsperson with special voting power. Their vote counts as double during the day.',
            ],
            [
                'name' => 'Sheriff',
                'description' => 'A townsperson with investigative abilities. During the day, they can attempt to kill a player. If the player is not a Mafia, the Sheriff gets killed.',
            ],
            // Add more roles as needed
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
}

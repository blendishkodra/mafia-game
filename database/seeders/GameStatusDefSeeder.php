<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameStatusDefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameStatuses = [
            ['name' => 'Ongoing'],
            ['name' => 'Finished'],
        ];

        // Insert game statuses into the database
        foreach ($gameStatuses as $status) {
            DB::table('game_status_def')->insert($status);
        }
    }
}

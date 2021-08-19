<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            Team::insert([
                ['name' => 'Chelsea', 'image' => 'Chelsea.png'],
                ['name' => 'Arsenal', 'image' => 'arsenal.png'],
                ['name' => 'Manchester City', 'image' => 'manchester.png'],
                ['name' => 'Liverpool', 'image' => 'liverpool.png'],
            ]);
        });
    }
}

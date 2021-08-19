<?php

namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            Week::insert([
                ['name' => '1 st', 'order'=>1],
                ['name' => '2 nd', 'order'=>2],
                ['name' => '3 rd', 'order'=>3],
                ['name' => '4 th', 'order'=>4],
                ['name' => '5 th', 'order'=>5],
                ['name' => '6 th', 'order'=>6],
            ]);
        });
    }
}

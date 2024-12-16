<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
                 'attendance_id' => 1,
                 'start_time' => Carbon::parse('17:24:09'),
              'end_time' => Carbon::parse('17:24:10')
                ];
                DB::table('rests')->insert($param);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hris_shifts')->insert([
            [
                'code'           => 'SH001',
                'name'           => 'Shift Pagi',
                'start_time'     => '08:00:00',
                'end_time'       => '17:00:00',
                'break_duration' => 60,
                'is_overnight'   => false,
                'is_active'      => true,
                'created_by'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SH002',
                'name'           => 'Shift Siang',
                'start_time'     => '13:00:00',
                'end_time'       => '22:00:00',
                'break_duration' => 60,
                'is_overnight'   => false,
                'is_active'      => true,
                'created_by'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SH003',
                'name'           => 'Shift Malam',
                'start_time'     => '22:00:00',
                'end_time'       => '06:00:00',
                'break_duration' => 60,
                'is_overnight'   => true,
                'is_active'      => true,
                'created_by'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SH004',
                'name'           => 'Shift Normal',
                'start_time'     => '09:00:00',
                'end_time'       => '18:00:00',
                'break_duration' => 60,
                'is_overnight'   => false,
                'is_active'      => true,
                'created_by'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
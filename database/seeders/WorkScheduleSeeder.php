<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil shift ID yang sudah di-seed
        $shiftPagi   = DB::table('hris_shifts')->where('code', 'SH001')->value('id');
        $shiftSiang  = DB::table('hris_shifts')->where('code', 'SH002')->value('id');
        $shiftMalam  = DB::table('hris_shifts')->where('code', 'SH003')->value('id');
        $shiftNormal = DB::table('hris_shifts')->where('code', 'SH004')->value('id');

        DB::table('hris_work_schedules')->insert([
            [
                'code'       => 'WS001',
                'name'       => '5 Hari Kerja (Senin–Jumat)',
                'work_days'  => json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri']),
                'shift_id'   => $shiftNormal,
                'is_active'  => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'WS002',
                'name'       => '6 Hari Kerja (Senin–Sabtu)',
                'work_days'  => json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
                'shift_id'   => $shiftPagi,
                'is_active'  => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'WS003',
                'name'       => 'Jadwal Shift Siang',
                'work_days'  => json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
                'shift_id'   => $shiftSiang,
                'is_active'  => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'WS004',
                'name'       => 'Jadwal Shift Malam',
                'work_days'  => json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
                'shift_id'   => $shiftMalam,
                'is_active'  => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
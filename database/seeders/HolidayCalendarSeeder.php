<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidayCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            // ===== NASIONAL 2025 =====
            [
                'name'        => 'Tahun Baru Masehi',
                'date'        => '2025-01-01',
                'type'        => 'national',
                'description' => 'Libur Tahun Baru 2025',
                'is_active'   => true,
            ],
            [
                'name'        => 'Isra Miraj Nabi Muhammad SAW',
                'date'        => '2025-01-27',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Tahun Baru Imlek',
                'date'        => '2025-01-29',
                'type'        => 'national',
                'description' => 'Tahun Baru Imlek 2576',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Raya Nyepi',
                'date'        => '2025-03-29',
                'type'        => 'national',
                'description' => 'Tahun Baru Saka 1947',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Raya Idul Fitri',
                'date'        => '2025-03-31',
                'type'        => 'national',
                'description' => '1 Syawal 1446 H',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Raya Idul Fitri (Hari ke-2)',
                'date'        => '2025-04-01',
                'type'        => 'national',
                'description' => '2 Syawal 1446 H',
                'is_active'   => true,
            ],
            [
                'name'        => 'Wafat Yesus Kristus',
                'date'        => '2025-04-18',
                'type'        => 'national',
                'description' => 'Jumat Agung',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Paskah',
                'date'        => '2025-04-20',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Buruh Internasional',
                'date'        => '2025-05-01',
                'type'        => 'national',
                'description' => 'May Day',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kenaikan Yesus Kristus',
                'date'        => '2025-05-29',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Raya Waisak',
                'date'        => '2025-05-12',
                'type'        => 'national',
                'description' => 'Hari Raya Waisak 2569',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Lahir Pancasila',
                'date'        => '2025-06-01',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Raya Idul Adha',
                'date'        => '2025-06-07',
                'type'        => 'national',
                'description' => '10 Dzulhijjah 1446 H',
                'is_active'   => true,
            ],
            [
                'name'        => 'Tahun Baru Islam',
                'date'        => '2025-06-27',
                'type'        => 'national',
                'description' => '1 Muharram 1447 H',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Kemerdekaan Republik Indonesia',
                'date'        => '2025-08-17',
                'type'        => 'national',
                'description' => 'HUT RI ke-80',
                'is_active'   => true,
            ],
            [
                'name'        => 'Maulid Nabi Muhammad SAW',
                'date'        => '2025-09-05',
                'type'        => 'national',
                'description' => '12 Rabiul Awal 1447 H',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Natal',
                'date'        => '2025-12-25',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hari Natal (Hari ke-2)',
                'date'        => '2025-12-26',
                'type'        => 'national',
                'description' => null,
                'is_active'   => true,
            ],

            // ===== LIBUR PERUSAHAAN =====
            [
                'name'        => 'Cuti Bersama Idul Fitri',
                'date'        => '2025-03-28',
                'type'        => 'company',
                'description' => 'Cuti bersama sebelum Idul Fitri',
                'is_active'   => true,
            ],
            [
                'name'        => 'Cuti Bersama Idul Fitri',
                'date'        => '2025-04-02',
                'type'        => 'company',
                'description' => 'Cuti bersama setelah Idul Fitri',
                'is_active'   => true,
            ],
            [
                'name'        => 'Cuti Bersama Natal',
                'date'        => '2025-12-26',
                'type'        => 'company',
                'description' => 'Cuti bersama Natal',
                'is_active'   => true,
            ],
            [
                'name'        => 'Cuti Bersama Tahun Baru',
                'date'        => '2025-12-31',
                'type'        => 'company',
                'description' => 'Cuti bersama menjelang Tahun Baru',
                'is_active'   => true,
            ],
        ];

        $now = now();
        $rows = array_map(fn($h) => array_merge($h, [
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]), $holidays);

        DB::table('hris_holiday_calendars')->insert($rows);
    }
}
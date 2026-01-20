<?php

namespace Database\Seeders;

use App\Models\Employe;
use Illuminate\Database\Seeder;

class HrisEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'div_code'        => 'IT',
                'dept_code'       => 'WEB',
                'full_name'       => 'Budi Santoso',
                'email'           => 'budi.s@perusahaan.com',
                'phone_number'    => '081234567890',
                'gender'          => 'L',
                'position'        => 'Senior Developer',
                'employee_status' => 'Permanent',
                'join_date'       => '2022-01-15',
                'is_active'       => true,
            ],
            [
                'div_code'        => 'HRD',
                'dept_code'       => 'REC',
                'full_name'       => 'Siti Aminah',
                'email'           => 'siti.a@perusahaan.com',
                'phone_number'    => '081299887766',
                'gender'          => 'P',
                'position'        => 'HR Recruitment',
                'employee_status' => 'Contract',
                'join_date'       => '2023-05-10',
                'is_active'       => true,
            ],
            [
                'div_code'        => 'FIN',
                'dept_code'       => 'PAY',
                'full_name'       => 'Andi Wijaya',
                'email'           => 'andi.w@perusahaan.com',
                'phone_number'    => '081344556677',
                'gender'          => 'L',
                'position'        => 'Payroll Specialist',
                'employee_status' => 'Permanent',
                'join_date'       => '2021-11-20',
                'is_active'       => true,
            ],
        ];

        foreach ($employees as $emp) {
            // Menggunakan email sebagai unik key agar tidak duplikat saat dijalankan ulang
            Employe::updateOrCreate(['email' => $emp['email']], $emp);
        }
    }
}
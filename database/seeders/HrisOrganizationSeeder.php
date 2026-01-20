<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Department;
use Illuminate\Database\Seeder;

class HrisOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Isi Data Divisi
        $divisions = [
            ['div_code' => 'IT', 'div_name' => 'Information Technology'],
            ['div_code' => 'HRD', 'div_name' => 'Human Resources Development'],
            ['div_code' => 'FIN', 'div_name' => 'Finance & Accounting'],
        ];

        foreach ($divisions as $div) {
            Division::updateOrCreate(['div_code' => $div['div_code']], $div);
        }

        // 2. Isi Data Departemen
        $departments = [
            // Departemen di bawah IT
            ['dept_code' => 'WEB', 'dept_name' => 'Web Development', 'div_code' => 'IT'],
            ['dept_code' => 'NET', 'dept_name' => 'Network & Infrastructure', 'div_code' => 'IT'],
            
            // Departemen di bawah HRD
            ['dept_code' => 'REC', 'dept_name' => 'Recruitment', 'div_code' => 'HRD'],
            ['dept_code' => 'TRN', 'dept_name' => 'Training & Development', 'div_code' => 'HRD'],
            
            // Departemen di bawah Finance
            ['dept_code' => 'PAY', 'dept_name' => 'Payroll', 'div_code' => 'FIN'],
            ['dept_code' => 'ACC', 'dept_name' => 'Accounting', 'div_code' => 'FIN'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['dept_code' => $dept['dept_code']], $dept);
        }
    }
}
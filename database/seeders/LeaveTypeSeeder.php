<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            [
                'code'               => 'LT001',
                'name'               => 'Cuti Tahunan',
                'max_days'           => 12,
                'is_paid'            => true,
                'carry_over'         => true,
                'max_carry_over_days'=> 6,
                'gender_specific'    => 'all',
                'requires_document'  => false,
                'min_working_days'   => 365,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp',    'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'hr',            'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'code'               => 'LT002',
                'name'               => 'Cuti Sakit',
                'max_days'           => 30,
                'is_paid'            => true,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'all',
                'requires_document'  => true,
                'min_working_days'   => 0,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'hr', 'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'code'               => 'LT003',
                'name'               => 'Cuti Melahirkan',
                'max_days'           => 90,
                'is_paid'            => true,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'female',
                'requires_document'  => true,
                'min_working_days'   => 0,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp',    'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'division_head', 'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 3, 'approver_type' => 'hr',            'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'code'               => 'LT004',
                'name'               => 'Cuti Ayah (Paternity Leave)',
                'max_days'           => 2,
                'is_paid'            => true,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'male',
                'requires_document'  => true,
                'min_working_days'   => 0,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp', 'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'hr',         'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'code'               => 'LT005',
                'name'               => 'Cuti Duka',
                'max_days'           => 3,
                'is_paid'            => true,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'all',
                'requires_document'  => false,
                'min_working_days'   => 0,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp', 'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'hr',         'approver_user_id' => null, 'is_mandatory' => false],
                ],
            ],
            [
                'code'               => 'LT006',
                'name'               => 'Cuti Pernikahan',
                'max_days'           => 3,
                'is_paid'            => true,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'all',
                'requires_document'  => true,
                'min_working_days'   => 0,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp',    'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'division_head', 'approver_user_id' => null, 'is_mandatory' => false],
                    ['step_order' => 3, 'approver_type' => 'hr',            'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
            [
                'code'               => 'LT007',
                'name'               => 'Cuti Tanpa Bayar (Unpaid Leave)',
                'max_days'           => 30,
                'is_paid'            => false,
                'carry_over'         => false,
                'max_carry_over_days'=> 0,
                'gender_specific'    => 'all',
                'requires_document'  => false,
                'min_working_days'   => 365,
                'is_active'          => true,
                'approval_steps'     => [
                    ['step_order' => 1, 'approver_type' => 'direct_svp',    'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 2, 'approver_type' => 'division_head', 'approver_user_id' => null, 'is_mandatory' => true],
                    ['step_order' => 3, 'approver_type' => 'hr',            'approver_user_id' => null, 'is_mandatory' => true],
                ],
            ],
        ];

        $now = now();

        foreach ($leaveTypes as $lt) {
            $steps = $lt['approval_steps'];
            unset($lt['approval_steps']);

            // Insert leave type
            $leaveTypeId = DB::table('hris_leave_types')->insertGetId(array_merge($lt, [
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            // Insert approval steps
            $stepRows = array_map(fn($s) => array_merge($s, [
                'leave_type_id' => $leaveTypeId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]), $steps);

            DB::table('hris_leave_type_approval_steps')->insert($stepRows);
        }
    }
}
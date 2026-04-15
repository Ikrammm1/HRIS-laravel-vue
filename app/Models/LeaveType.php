<?php

// ============================================================
// app/Models/LeaveType.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $table = 'hris_leave_types';
    protected $fillable = [
        'code', 'name', 'max_days', 'is_paid', 'carry_over',
        'max_carry_over_days', 'gender_specific', 'requires_document',
        'min_working_days', 'is_active', 'created_by'
    ];

    protected $casts = [
        'is_paid'           => 'boolean',
        'carry_over'        => 'boolean',
        'requires_document' => 'boolean',
        'is_active'         => 'boolean',
    ];

    public function approvalSteps(): HasMany
    {
        return $this->hasMany(LeaveTypeApprovalStep::class)->orderBy('step_order');
    }
}
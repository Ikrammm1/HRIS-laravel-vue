<?php

// ============================================================
// app/Models/LeaveTypeApprovalStep.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveTypeApprovalStep extends Model
{
    protected $table = 'hris_leave_type_approval_steps';
    protected $fillable = [
        'leave_type_id', 'step_order', 'approver_type',
        'approver_user_id', 'is_mandatory'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
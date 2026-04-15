<?php
// ============================================================
// app/Models/WorkSchedule.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSchedule extends Model
{
    protected $table = 'hris_work_schedules';
    protected $fillable = [
        'code', 'name', 'work_days', 'shift_id', 'is_active', 'created_by'
    ];

    protected $casts = [
        'work_days' => 'array',
        'is_active' => 'boolean',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
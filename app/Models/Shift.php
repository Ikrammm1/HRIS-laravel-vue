<?php
// ============================================================
// app/Models/Shift.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $table = 'hris_shifts';
    protected $fillable = [
        'code', 'name', 'start_time', 'end_time',
        'break_duration', 'is_overnight', 'is_active', 'created_by'
    ];

    protected $casts = [
        'is_overnight' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function workSchedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class);
    }
}
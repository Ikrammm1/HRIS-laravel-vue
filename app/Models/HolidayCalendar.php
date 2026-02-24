<?php
// ============================================================
// app/Models/HolidayCalendar.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidayCalendar extends Model
{
    protected $table = 'hris_holiday_calendars';
    protected $fillable = [
        'name', 'date', 'type', 'description', 'is_active', 'created_by'
    ];

    protected $casts = [
        'date'      => 'date',
        'is_active' => 'boolean',
    ];
}
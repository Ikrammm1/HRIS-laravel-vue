<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'hris_departments';
    protected $fillable = ['dept_code', 'dept_name', 'div_code'];

    public function division()
    {
        return $this->belongsTo(Division::class, 'div_code', 'div_code');
    }
}

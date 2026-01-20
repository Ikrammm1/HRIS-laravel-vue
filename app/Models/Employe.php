<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    public $table = 'hris_employes';
    protected $guarded = [];

    public function division()
    {
        return $this->belongsTo(Division::class, 'div_code', 'div_code');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_code', 'dept_code');
    }
}

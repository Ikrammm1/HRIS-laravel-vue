<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'hris_divisions';
    protected $fillable = ['div_code', 'div_name'];

    public function departments()
    {
        return $this->hasMany(Department::class, 'div_code', 'div_code');
    }
}

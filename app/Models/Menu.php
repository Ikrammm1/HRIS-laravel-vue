<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'hris_menus';
    protected $guarded = [];


    public function authorizationGroups()
    {
        return $this->belongsToMany(
            AuthorizationGroup::class,
            'hris_menu_auth_groups',
            'menu_id',
            'group_id'
        );
    }
}

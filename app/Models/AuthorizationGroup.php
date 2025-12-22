<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizationGroup extends Model
{
    public $table = 'hris_authorization_group';
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'hris_user_auth_groups',
            'group_id',
            'user_id'
        );
    }

    public function menus()
    {
        return $this->belongsToMany(
            Menu::class,
            'hris_menu_auth_groups',
            'group_id',
            'menu_id'
        );
    }
}


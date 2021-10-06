<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'mcs_role';
    protected $primaryKey = 'role_id';
    protected $fillable = ['name', 'st'];
    public $timestamps = false;

    public function menu_carta_role()
    {
        return $this->hasMany(MenuCartaRole::class, 'role_id', 'role_id');
    }
}

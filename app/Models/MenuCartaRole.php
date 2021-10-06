<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCartaRole extends Model
{
    protected $table = 'menu_carta_role';
    protected $primaryKey = null;
    protected $fillable = ['menu_carta_id', 'role_id', 'is_view', 'is_create', 'is_edit', 'is_delete', 'is_detail', 'is_approval'];
    public $timestamps = false;
    public $incrementing = false;

    public function menu_carta()
    {
        return $this->belongsTo('App\Models\MenuCarta', 'menu_carta_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
}

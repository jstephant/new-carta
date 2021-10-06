<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCarta extends Model
{
    protected $table = 'menu_carta';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'is_main_menu', 'parent_id', 'sequence', 'is_active', 'link', 'icon', 'has_sub_menu', 'is_view', 'is_create', 'is_edit', 'is_delete', 'is_detail', 'is_approval'];
    public $timestamps = false;

    public function menu_carta_role()
    {
        return $this->hasMany('App\Models\MenuCartaRole', 'menu_carta_id', 'id');
    }
}

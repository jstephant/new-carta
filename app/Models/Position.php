<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'mcs_position';
    protected $primaryKey = 'position_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'position_id', 'position_id');
    }
}

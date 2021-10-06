<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $table = 'mcs_occupation';
    protected $primaryKey = 'id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'occupation_id', 'id');
    }
}

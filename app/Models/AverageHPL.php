<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AverageHPL extends Model
{
    protected $table = 'mcs_avg_hpl';
    protected $primaryKey = 'id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'average_hpl_id', 'id');
    }
}

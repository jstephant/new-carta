<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    protected $table = 'mcs_data_source';
    protected $primaryKey = 'data_source_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'data_source_id', 'data_source_id');
    }
}

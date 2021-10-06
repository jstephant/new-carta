<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvinceTeritory extends Model
{
    protected $table = 'mcs_province_teritory';

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }

    public function teritory()
    {
        return $this->belongsTo('App\Models\Teritory', 'teritory_id');
    }
}

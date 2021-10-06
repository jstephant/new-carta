<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'mcs_city';
    protected $primaryKey = 'city_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'city_id', 'city_id');
    }

    public function subdistrict()
    {
        return $this->hasMany('App\Models\Subdistrict', 'subdistrict_id', 'subdistrict_id');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }
}

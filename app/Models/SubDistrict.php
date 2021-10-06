<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'mcs_subdistrict';
    protected $primaryKey = 'subdistrict_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'sub_district_id', 'subdistrict_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function village()
    {
        return $this->hasMany('App\Models\Village', 'subdistrict_id', 'subdistrict_id');
    }
}

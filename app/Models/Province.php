<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'mcs_province';
    protected $primaryKey = 'province_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'province_id', 'province_id');
    }

    public function province_teritory()
    {
        return $this->hasMany('App\Models\ProvinceTeritory', 'province_id', 'province_id');
    }

    public function city()
    {
        return $this->hasMany('App\Models\City', 'city_id', 'city_id');
    }
}
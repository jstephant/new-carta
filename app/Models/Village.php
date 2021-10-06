<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'mcs_village';
    protected $primaryKey = 'village_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'village_id', 'village_id');
    }

    public function customer_account()
    {
        return $this->hasMany('App\Models\CustomerAccount', 'village_id', 'village_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo('App\Models\Subdistrict', 'subdistrict_id');
    }
}

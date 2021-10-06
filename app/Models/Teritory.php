<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teritory extends Model
{
    protected $table = 'mcs_teritory';
    protected $primaryKey = 'teritory_id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'teritory_id', 'teritory_id');
    }

    public function customer_account()
    {
        return $this->hasMany('App\Models\CustomerAccount', 'teritory_id', 'teritory_id');
    }

    public function province_teritory()
    {
        return $this->hasMany('App\Models\ProvinceTeritory', 'teritory_id', 'teritory_id');
    }
}

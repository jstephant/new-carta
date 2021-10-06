<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    protected $table = 'mcs_sales_networking';
    protected $primaryKey = 'sales_networking_id';
    protected $keyType = 'string';

    public function customer_category()
    {
        return $this->belongsTo('App\Models\CustomerCategory', 'customer_category_id');
    }

    public function teritory()
    {
        return $this->belongsTo('App\Models\Teritory', 'teritory_id');
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'village_id');
    }

    public function data_source()
    {
        return $this->belongsTo('App\Models\DataSource', 'data_source_id');
    }

    public function contact()
    {
        return $this->hasMany('App\Models\AccountContact', 'sales_networking_id', 'sales_networking_id');
    }

    public function contact_one()
    {
        return $this->hasOne('App\Models\AccountContact', 'sales_networking_id');
    }

    public function customer_reward()
    {
        return $this->hasMany('App\Models\CustomerReward', 'sales_networking_id', 'sales_networking_id');
    }

    public function assigned_to()
    {
        return $this->belongsTo('App\Models\User', 'nik');
    }

    public function account_follow_up()
    {
        return $this->hasMany('App\Models\AccountFollowup', 'sales_networking_id', 'sales_networking_id');
    }

    public function leads()
    {
        return $this->belongsTo('App\Models\Leads', 'lead_id');
    }
}

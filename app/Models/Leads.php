<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $table = 'mcs_leads';
    protected $primaryKey = 'id';
    
    protected $fillable = ['customer_name', 'customer_category_id', 'contact_title_id', 'contact_name', 'contact_phone', 'contact_email',
        'occupation_id', 'other_occupation', 'position_id', 'customer_dob', 'detail_address', 'teritory_id', 'province_id', 'city_id',
        'sub_district_id', 'village_id', 'postal_code', 'is_e_catalog', 'average_hpl_id', 'data_source_id', 'assigned_to', 'notes',
        'status_id', 'products', 'st', 'created_at', 'created_by', 'updated_at', 'updated_by', 'duplicate', 'brand_id'
    ];

    public function customer_category()
    {
        return $this->belongsTo('App\Models\CustomerCategory', 'customer_category_id');
    }

    public function contact_title()
    {
        return $this->belongsTo('App\Models\ContactTitle', 'contact_title_id');
    }

    public function occupation()
    {
        return $this->belongsTo('App\Models\Occupation', 'occupation_id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position', 'position_id');
    }

    public function teritory()
    {
        return $this->belongsTo('App\Models\Teritory', 'teritory_id');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo('App\Models\SubDistrict', 'sub_district_id');
    }
    
    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'village_id');
    }

    public function average_hpl()
    {
        return $this->belongsTo('App\Models\AverageHPL', 'average_hpl_id');
    }

    public function data_source()
    {
        return $this->belongsTo('App\Models\DataSource', 'data_source_id');
    }

    public function assigned_to()
    {
        return $this->belongsTo('App\Models\User', 'assigned_to');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\LeadStatus', 'status_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function follow_up()
    {
        return $this->hasMany('App\Models\FollowUp', 'lead_id', 'id');
    }
    
    public function customer_account()
    {
        return $this->hasOne('App\Models\CustomerAccount', 'lead_id', 'id');
    }
}

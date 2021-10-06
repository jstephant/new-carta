<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFollowup extends Model
{
    protected $table = 'mcs_sales_networking_visit';
    protected $primaryKey = 'sales_networking_visit_id';

    public function customer_account()
    {
        return $this->belongsTo('App\Models\CustomerAccount', 'sales_networking_id');
    }

    public function product()
    {
        return $this->hasMany('App\Models\AccountFollowupProduct', 'sales_networking_visit_id', 'sales_networking_visit_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\FollowupType', 'visit_type_id');
    }

    public function lead_status()
    {
        return $this->belongsTo('App\Models\FollowupLeadStatus', 'lead_status_id');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\AccountFollowupPhoto', 'sales_networking_visit_id');
    }
}

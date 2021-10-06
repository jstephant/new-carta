<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFollowupProduct extends Model
{
    protected $table = 'mcs_sales_networking_visit_product';
    protected $primaryKey = 'sales_networking_visit_product_id';
    public $timestamps = false;

    public function account_follow_up()
    {
        return $this->belongsTo('App\Models\AccountFollowup', 'sales_networking_visit_id');
    }

    public function product_carta()
    {
        return $this->hasMany('App\Models\AccountFollowupProductcarta', 'sales_networking_visit_product_id', 'sales_networking_visit_product_id');
    }

    public function sales_approval_status()
    {
        return $this->belongsTo('App\Models\ApprovalStatus', 'approval_status');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFollowupPhoto extends Model
{
    protected $table = 'mcs_sales_networking_visit_photo';
    protected $primaryKey = 'sales_networking_visit_photo_id';

    public function account_follow_up()
    {
        return $this->belongsTo('App\Models\AccountFollowup', 'sales_networking_visit_id');
    }
}

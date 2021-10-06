<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowupProduct extends Model
{
    protected $table = 'mcs_follow_up_product';
    protected $primaryKey = 'id';

    public function follow_up()
    {
        return $this->belongsTo('App\Models\FollowUp', 'follow_up_id');
    }

    public function product_carta()
    {
        return $this->hasMany('App\Models\FollowUpProductCarta', 'follow_up_product_id', 'id');
    }
}

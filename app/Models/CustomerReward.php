<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReward extends Model
{
    protected $table = 'mcs_customer_reward';
    protected $primaryKey = 'customer_reward_id';
    public $timestamps = false;

    public function customer_reward_item()
    {
        return $this->hasMany('App\Models\CustomerRewardItem', 'customer_reward_id', 'customer_reward_id');
    }

    public function customer_reward_status()
    {
        return $this->belongsTo('App\Models\CustomerRewardStatus', 'customer_reward_status_id');
    }
    
    public function customer_account()
    {
        return $this->belongsTo('App\Models\CustomerAccount', 'sales_networking_id');
    }

    public function request_user()
    {
        return $this->belongsTo('App\Models\User', 'request_by');
    }
}

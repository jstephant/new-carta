<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRewardStatus extends Model
{
    protected $table = 'mcs_customer_reward_status';
    protected $primaryKey = 'customer_reward_status_id';

    public function customer_reward()
    {
        return $this->hasMany('App\Models\CustomerReward', 'customer_reward_status_id', 'customer_reward_status_id');
    }
}

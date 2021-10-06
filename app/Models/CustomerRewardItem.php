<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRewardItem extends Model
{
    protected $table = 'mcs_customer_reward_item';
    protected $primaryKey = 'customer_reward_item_id';

    public function customer_reward()
    {
        return $this->belongsTo('App\Models\CustomerReward', 'customer_reward_id');
    }

    public function reward_item()
    {
        return $this->belongsTo('App\Models\RewardItem', 'reward_item_id');
    }
}

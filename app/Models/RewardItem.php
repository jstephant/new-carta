<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    protected $table = 'mcs_reward_item';
    protected $primaryKey = 'reward_item_id';
    protected $fillable = ['reward_item_id', 'item_code', 'description', 'points_required', 'notes'];
    public $timestamps = false;

    public function reward_item_segmen()
    {
        return $this->hasMany('App\Models\RewardItemSegmen', 'reward_item_id', 'reward_item_id');
    }

    public function customer_reward_item()
    {
        return $this->belongsTo('App\Models\CustomerRewardItem', 'reward_item_id');
    }
}

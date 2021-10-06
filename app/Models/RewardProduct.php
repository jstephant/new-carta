<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardProduct extends Model
{
    protected $table = 'mcs_reward_product';
    protected $fillable = ['reward_id', 'product_id'];
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function reward()
    {
        return $this->belongsTo('App\Models\Reward', 'reward_id');
    }

    public function master_product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}

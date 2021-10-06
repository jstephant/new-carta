<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardItemSegmen extends Model
{
    protected $table = 'mcs_reward_item_segmentation';
    protected $fillable = ['reward_item_id', 'customer_category_id'];
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function reward_item()
    {
        return $this->belongsTo('App\Models\RewardItem', 'reward_item_id');
    }

    public function customer_category()
    {
        return $this->belongsTo('App\Models\CustomerCategory', 'customer_category_id');
    }
}

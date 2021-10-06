<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $table = 'mcs_reward';
    protected $primaryKey = 'reward_id';
    protected $fillable = ['reward_id', 'description', 'customer_category_id', 'quantity', 'points', 'notes', 'type', 'created_at', 'created_by'];
    public $timestamps = false;

    public function customer_category()
    {
        return $this->belongsTo('App\Models\CustomerCategory', 'customer_category_id');
    }

    public function reward_product()
    {
        return $this->hasMany('App\Models\RewardProduct', 'reward_id', 'reward_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public function product_carta()
    {
        return $this->hasMany('App\Models\FollowupProductCarta', 'product_id', 'product_id');
    }
}

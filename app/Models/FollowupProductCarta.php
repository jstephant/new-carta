<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowupProductCarta extends Model
{
    protected $table = 'mcs_follow_up_product_carta';
    protected $primaryKey = 'id';

    public function follow_up_product()
    {
        return $this->belongsTo('App\Models\FollowupProduct', 'follow_up_product_id');
    }
    
    public function master_product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}

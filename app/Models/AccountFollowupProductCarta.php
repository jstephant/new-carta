<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFollowupProductCarta extends Model
{
    protected $table = 'mcs_sales_networking_visit_product_carta';
    protected $primaryKey = 'sales_networking_visit_product_carta_id';

    public function follow_up_product()
    {
        return $this->belongsTo('App\Models\AccountFollowupProduct', 'sales_networking_visit_product_id');
    }
    
    public function master_product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}

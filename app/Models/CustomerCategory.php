<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    protected $table = 'mcs_customer_category';
    protected $primaryKey = 'customer_category_id';
    
    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'customer_category_id', 'customer_category_id');
    }
}

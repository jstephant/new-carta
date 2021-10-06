<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadProduct extends Model
{
    protected $table = 'mcs_lead_product';
    protected $fillable = ['lead_id', 'product_id', 'qty'];
    public $timestamps = false;
}

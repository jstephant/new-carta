<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountContactEmail extends Model
{
    protected $table = 'mcs_sales_networking_contact_email';
    protected $primaryKey = 'sales_networking_contact_email_id';
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo('App\Models\AccountContact', 'sales_networking_contact');
    }
}

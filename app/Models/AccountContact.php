<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountContact extends Model
{
    protected $table = 'mcs_sales_networking_contact';
    protected $primaryKey = 'sales_networking_contact_id';
    public $timestamps = false;

    public function customer_account()
    {
        return $this->belongsTo('App\Models\CustomerAccount', 'sales_networking_id');
    }

    public function contact_phone()
    {
        return $this->hasMany('App\Models\AccountContactPhone', 'sales_networking_contact_id', 'sales_networking_contact_id');
    }

    public function contact_phone_one()
    {
        return $this->hasOne('App\Models\AccountContactPhone', 'sales_networking_contact_id')->where('phone_type_id', 2);
    }

    public function contact_email()
    {
        return $this->hasMany('App\Models\AccountContactEmail', 'sales_networking_contact_id', 'sales_networking_contact_id');
    }

    public function contact_email_one()
    {
        return $this->hasOne('App\Models\AccountContactEmail', 'sales_networking_contact_id')->where('email_type_id', 2);
    }
}

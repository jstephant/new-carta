<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'mcs_user';
    protected $primaryKey = 'nik';
    protected $fillable = ['nik', 'role_id', 'teritory_id', 'name', 'email', 'st', 'nik_atasan', 'sales_org', 'nik_atasan2'];
    public $timestamps = false;

    public function lead_assigned_to()
    {
        return $this->hasMany('App\Models\Leads', 'assigned_to', 'nik');
    }

    public function lead_created_by()
    {
        return $this->hasMany('App\Models\Leads', 'created_by', 'nik');
    }

    public function lead_updated_by()
    {
        return $this->hasMany('App\Models\Leads', 'updated_by', 'nik');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function teritory()
    {
        return $this->belongsTo(Teritory::class, 'teritory_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table = 'mcs_lead_status';
    protected $primaryKey = 'id';

    public function lead()
    {
        return $this->hasMany('App\Models\Leads', 'status_id', 'id');
    }
}

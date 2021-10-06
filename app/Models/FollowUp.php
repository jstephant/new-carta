<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table = 'mcs_follow_up';
    protected $primaryKey = 'id';

    public function lead()
    {
        return $this->belongsTo('App\Models\Leads', 'lead_id');
    }

    public function product()
    {
        return $this->hasMany('App\Models\FollowupProduct', 'follow_up_id', 'id');
    }

    public function photo()
    {
        return $this->hasMany('App\Models\FollowupPhoto', 'follow_up_id', 'id');
    }

    public function lead_status()
    {
        return $this->belongsTo('App\Models\FollowupLeadStatus', 'follow_up_lead_status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\FollowupType', 'follow_up_type_id');
    }
}

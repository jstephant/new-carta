<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAssignedLead extends Model
{
    protected $table = 'mcs_log_assigned_lead';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id', 'assigned_to', 'assigned_to_before', 'created_by', 'created_at'];
    public $timestamps = false;
}

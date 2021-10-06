<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowupPhoto extends Model
{
    protected $table = 'mcs_follow_up_photo';
    protected $primaryKey = 'id';

    public function follow_up()
    {
        return $this->belongsTo('App\Models\FollowUp', 'follow_up_id');
    }
}

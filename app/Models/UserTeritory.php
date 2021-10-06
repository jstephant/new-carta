<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTeritory extends Model
{
    protected $table = 'mcs_user_teritory';
    protected $primaryKey = 'user_teritory_id';
    protected $fillable = ['nik', 'teritory_id'];
    public $timestamps = false;

    public function teritory()
    {
        return $this->belongsTo('App\Models\Teritory', 'teritory_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'mcs_log_activity';
    protected $primaryKey = 'id';
    protected $fillable = ['nik', 'type', 'created_at', 'source'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }
}

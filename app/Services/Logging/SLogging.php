<?php

namespace App\Services\Logging;

use App\Models\LogActivity;
use App\Services\Logging\ILogging;
use Illuminate\Support\Facades\DB;

class SLogging implements ILogging
{
    private $logActivity;

    public function __construct(LogActivity $logActivity)
    {
        $this->logActivity = $logActivity;    
    }

    public function listLogging($start_date, $end_date, $start, $length, $order)
    {
        $logs = $this->logActivity->with('user')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);

        $count = $logs->count();

        if($length!=-1)
        {
            $logs = $logs->offset($start)->limit($length);
        }

        $logs = $logs->orderby('created_at', 'desc')->get();
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $logs->toArray(),
        ];

        return $data;
    }
}
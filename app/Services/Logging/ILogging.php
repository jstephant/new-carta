<?php

namespace App\Services\Logging;

interface ILogging
{
    public function listLogging($start_date, $end_date, $start, $length, $order);
}
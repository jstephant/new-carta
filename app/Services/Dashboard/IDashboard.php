<?php

namespace App\Services\Dashboard;

interface IDashboard
{
    // lead status
    public function getLeadStatus();
    public function totalLeadByStatus($status_id, $start_date, $end_date);

    // follow up lead status
    public function getFollowupLeadStatus();
    public function totalFollowupByStatus($status_id, $start_date, $end_date);

    // account summary
    public function totalNewAccountByDate($start_date, $end_date);
    public function totalAccount();

    // point
    public function totalPoints();
    public function totalRedeem();
    public function totalFloatingPoint();

    // sales summary
    public function totalSalesByDate($start_date, $end_date);
    public function totalSalesByDate2($start_date, $end_date);
}
<?php

namespace App\Services\FollowUp;

interface IFollowUp
{
    public function querySelect();
    public function findByRelatedId($id);
    public function getFollowUpDate($id, $order);
    public function getTotalFollowUp($id);
    public function getListFollowUp($id, $start_date, $end_date, $start, $length, $order);
    public function getFollowUpPhoto($id);
    public function getTotalFollowUpProduct($id);

    public function getSalesDetailById($id);
    public function followupHistoryJoin($id, $start_date, $end_date, $start, $length, $order);
}
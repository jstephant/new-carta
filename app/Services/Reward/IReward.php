<?php

namespace App\Services\Reward;

interface IReward
{
    // customer reward
    public function getCustomerRewards($followup_start, $followup_to, $purchase_start = '', $purchase_to = '', $type = '', $area = '', $assigned_to = '', $name = '', $start, $length, $order);
    public function findCustomerRewardById($id);
    public function getLastPurchasedDate($sales_networking_id);
    public function getLastFollowUpDate($sales_networking_id);
    public function getTotalFollowUp($sales_networking_id);

    // manage reward
    public function getRewards($start, $length, $order);
    public function createRewards($input);
    public function deleteRewards($id);
    public function createRewardProduct($input);
    public function deleteRewardProductAll($reward_id);

    // manage reward items
    public function getRewardItems($start, $length, $order);
    public function createRewardItems($input);
    public function deleteRewardItems($id);
    public function createRewardItemSegmentation($input);
    public function deleteRewardItemSegmentationAll($reward_item_id);

    // sales approval
    public function getListSalesApproval($start_date, $end_date, $status='', $name='', $start, $length, $order);
    public function salesApproval($sales_networking_visit_id, $input);

    // redeem history
    public function getRedeemHistory($id);
    public function approveRequest($id, $nik);
    public function rejectRequest($id);
    public function deliverRequest($id);
}
<?php

namespace App\Services\Dashboard;

use App\Models\AccountFollowupProductCarta;
use App\Models\CustomerAccount;
use App\Models\CustomerReward;
use App\Models\FollowUp;
use App\Models\FollowupProduct;
use App\Models\Leads;
use App\Models\LeadStatus;
use App\Models\VisitLeadStatus;
use App\Services\Dashboard\IDashboard;
use Illuminate\Support\Facades\DB;

class SDashboard implements IDashboard
{
    private $leadStatus;
    private $leads;
    private $visitLeadStatus;
    private $followUp;
    private $customerAccount;
    private $followupProduct;
    private $customerReward;
    private $accountFollowupProductCarta;

    public function __construct(
        LeadStatus $leadStatus, 
        Leads $leads, 
        VisitLeadStatus $visitLeadStatus, 
        FollowUp $followUp, 
        CustomerAccount $customerAccount,
        FollowupProduct $followupProduct,
        CustomerReward $customerReward,
        AccountFollowupProductCarta $accountFollowupProductCarta
    )
    {
        $this->leadStatus = $leadStatus;
        $this->leads = $leads;
        $this->visitLeadStatus = $visitLeadStatus;
        $this->followUp = $followUp;
        $this->customerAccount = $customerAccount;
        $this->followupProduct = $followupProduct;
        $this->customerReward = $customerReward;
        $this->accountFollowupProductCarta = $accountFollowupProductCarta;
    }

    public function getLeadStatus()
    {
        return $this->leadStatus->get();
    }

    public function totalLeadByStatus($status_id, $start_date, $end_date)
    {
        return $this->leads->where('status_id', $status_id)->whereBetween(DB::raw("DATE(created_at)"), [$start_date, $end_date])->get()->count();
    }

    public function getFollowupLeadStatus()
    {
        return $this->visitLeadStatus->get();
    }

    public function totalFollowupByStatus($status_id, $start_date, $end_date)
    {
        return $this->followUp->where('st', 1)->where('follow_up_lead_status_id', $status_id)->whereBetween(DB::raw("TO_DATE(follow_up_date, 'DD-MM-YYYY')"), [$start_date, $end_date])->get()->count();
    }

    public function totalNewAccountByDate($start_date, $end_date)
    {
        return $this->customerAccount->wherenotnull('lead_id')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get()->count();
    }

    public function totalAccount()
    {
        return $this->customerAccount->where('st', 1)->count();
    }

    public function totalPoints()
    {
        $total_floating = $this->totalFloatingPoint();
        $total_redeem = $this->totalRedeem();
        return $total_floating + $total_redeem;
    }

    public function totalRedeem()
    {
        $points = $this->customerReward
                        ->with(['customer_reward_item', 'customer_account'])
                        ->where('customer_reward_status_id', 3)
                        ->whereHas('customer_account', function($q) { $q->where('st', 1); })
                        ->get();

        $total_redeem = 0;
        foreach ($points as $value) {
            foreach ($value->customer_reward_item as $value2) {
                $total_redeem += ($value2->quantity * $value2->points_per_qty);
            }
        }
        return $total_redeem;
    }

    public function totalFloatingPoint()
    {
        return $this->customerAccount->sum('points');
    }


    public function totalSalesByDate($start_date, $end_date)
    {
        return DB::table('mcs_sales_networking_visit_product_carta as a')
                      ->leftjoin('mcs_sales_networking_visit_product as b', 'a.sales_networking_visit_product_id', 'b.sales_networking_visit_product_id')
                      ->leftjoin('mcs_sales_networking_visit as c', 'b.sales_networking_visit_id', 'c.sales_networking_visit_id')
                      ->leftjoin('mcs_sales_networking as d', 'c.sales_networking_id', 'd.sales_networking_id')
                      ->leftjoin('product as e', 'a.product_id', 'e.product_id')
                      ->select('sales_material_group3_desc', DB::raw('SUM(a.quantity) as total'))
                      ->where('d.st', 1)
                      ->where('c.st', 1)
                      ->where('b.merk_hpl_id', 1)
                      ->whereBetween(DB::raw("DATE(order_date)"), [$start_date, $end_date])
                      ->where('e.product_code', 'like', 'S0CT%')
                      ->groupby('sales_material_group3_desc')
                      ->get();
    }

    public function totalSalesByDate2($start_date, $end_date)
    {
        return DB::table('mcs_sales_networking_visit_product_carta as a')
                 ->leftjoin('mcs_sales_networking_visit_product as b', 'a.sales_networking_visit_product_id', 'b.sales_networking_visit_product_id')
                 ->leftjoin('mcs_sales_networking_visit as c', 'b.sales_networking_visit_id', 'c.sales_networking_visit_id')
                 ->leftjoin('mcs_sales_networking as d', 'c.sales_networking_id', 'd.sales_networking_id')
                 ->leftjoin('product as e', 'a.product_id', 'e.product_id')
                 ->select('sales_material_group2_desc', 'sales_material_group4_desc', DB::raw('SUM(a.quantity) as total'))
                 ->where('d.st', 1)
                 ->where('c.st', 1)
                 ->whereBetween(DB::raw("DATE(order_date)"), [$start_date, $end_date])
                 ->where('e.product_code', 'like', 'SECT%')
                 ->groupby('sales_material_group2_desc', 'sales_material_group4_desc')
                 ->get();
    }
}
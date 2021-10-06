<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\SDashboard;
use App\Services\SGlobal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $sGlobal;
    private $sDashboard;

    public function __construct(SGlobal $sGlobal, SDashboard $sDashboard)
    {
        $this->sGlobal = $sGlobal;
        $this->sDashboard = $sDashboard;
    }

    public function index()
    {
        $start_date = date('Y-m-d', strtotime('-7 day'));
        $end_date = date('Y-m-d');

        $arr_lead_status = array();
        $lead_status = $this->sDashboard->getLeadStatus();
        foreach ($lead_status as $value) {
            $arr_lead_status[] = array(
                'status_name' => $value->name,
                'total'       => $this->sDashboard->totalLeadByStatus($value->id, $start_date, $end_date)
            );
        }

        $arr_followup_status = array();
        $followup_status = $this->sDashboard->getFollowupLeadStatus();
        foreach ($followup_status as $key => $value) {
            $arr_followup_status[] = array(
                'status_name' => $value->status,
                'total'       => $this->sDashboard->totalFollowupByStatus($value->sales_networking_visit_lead_status_id, $start_date, $end_date)
            );
        }

        $total_new_account_summary = $this->sDashboard->totalNewAccountByDate($start_date, $end_date);
        $total_account = $this->sDashboard->totalAccount();

        $total_points = $this->sDashboard->totalPoints();
        $total_redeem = $this->sDashboard->totalRedeem();
        $total_float  = $this->sDashboard->totalFloatingPoint();

        $total_sales = $this->sDashboard->totalSalesByDate($start_date, $end_date);
        $total_sales2 = $this->sDashboard->totalSalesByDate2($start_date, $end_date);

        $data = array(
            'title'             => 'Home',
            'active_menu'       => 'Home',
            'lead_status'       => $arr_lead_status,
            'followup_status'   => $arr_followup_status,
            'total_new_account' => $total_new_account_summary,
            'total_account'     => $total_account,
            'total_points'      => $total_points,
            'total_redeem'      => $total_redeem,
            'total_floating'    => $total_float,
            'total_sales'       => $total_sales,
            'total_sales2'      => $total_sales2
        );

        return $this->sGlobal->view('home.index', $data);
    }

    public function getLeadStatus(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $arr_lead_status = array();
        $lead_status = $this->sDashboard->getLeadStatus();
        foreach ($lead_status as $key => $value) {
            $arr_lead_status[] = array(
                'status_name' => $value->name,
                'total'       => $this->sDashboard->totalLeadByStatus($value->id, $start_date, $end_date)
            );
        }
        return response()->json($arr_lead_status, 200);
    }

    public function getFollowupStatus(Request $request)
    {
        // gak perlu di convert karna followup_date itu tipe string
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $arr_followup_status = array();
        $followup_status = $this->sDashboard->getFollowupLeadStatus();
        foreach ($followup_status as $value) {
            $arr_followup_status[] = array(
                'status_name' => $value->status,
                'total'       => $this->sDashboard->totalFollowupByStatus($value->sales_networking_visit_lead_status_id, $start_date, $end_date)
            );
        }
        return response()->json($arr_followup_status, 200);
    }

    public function getTotalAccount(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        return $this->sDashboard->totalAccountByDate($start_date, $end_date);
    }

    public function getTotalSalesSummary(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $sales_summary = $this->sDashboard->totalSalesByDate($start_date, $end_date);
        $sales_summary2 = $this->sDashboard->totalSalesByDate2($start_date, $end_date);
        $data = array(
            'sales_hpl'    => $sales_summary,
            'sales_edging' => $sales_summary2 
        );
        return response()->json($data, 200);
    }
}

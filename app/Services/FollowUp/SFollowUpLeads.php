<?php

namespace App\Services\FollowUp;

use App\Models\FollowUp;
use App\Models\FollowupPhoto;
use App\Models\FollowupProduct;
use App\Models\FollowupProductCarta;
use Illuminate\Support\Facades\DB;

class SFollowUpLeads implements IFollowUp
{
    const IMG_DIR_DEV  = 'https://staging1.vivere.co.id/mycarta/assets/img/sales/sales_networking/';
    const IMG_DIR_PROD = 'https://apps.vivere.co.id/developer/mycarta/assets/img/sales/sales_networking/';

    private $followUp;
    private $followupPhoto;
    private $followupProduct;
    private $followupProductCarta;

    public function __construct(
        FollowUp $followUp, 
        FollowupPhoto $followupPhoto, 
        FollowupProduct $followupProduct,
        FollowupProductCarta $followupProductCarta
    )
    {
        $this->followUp = $followUp;
        $this->followupPhoto = $followupPhoto;
        $this->followupProduct = $followupProduct;
        $this->followupProductCarta = $followupProductCarta;
    }

    public function querySelect()
    {
        return $this->followUp
                    ->with(['type', 'lead_status'])
                    ->where('st', 1);
    }

    public function findByRelatedId($id)
    {
        return $this->followUp->where('lead_id', $id)->where('st', 1)->get();
    }

    public function getFollowUpDate($id, $order)
    {
        $follow_up = $this->followUp->where('lead_id', $id)->where('st', 1)->orderbyraw("TO_DATE(follow_up_date, 'DD-MM-YYYY') " .$order)->first();
        return ($follow_up) ? $follow_up->follow_up_date : null;
    }
    public function getTotalFollowUp($id)
    {
        return $this->followUp->where('lead_id', $id)->where('st', 1)->get()->count();
    }

    public function getListFollowUp($id, $start_date, $end_date, $start, $length, $order)
    {
        $follow_up = $this->querySelect();
        $follow_up = $follow_up->where('lead_id', $id)
                               ->whereBetween(DB::raw("TO_DATE(follow_up_date, 'DD-MM-YYYY')"), [$start_date, $end_date]);

        $count = $follow_up->count();

        if($length!=-1) {
            $follow_up = $follow_up->offset($start)->limit($length);
        } 
        
        $follow_up = $follow_up->get();
        foreach ($follow_up as $value) {
            $value->total_followup_product = $this->getTotalFollowUpProduct($value->id);
        }
        
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $follow_up->toArray(),
        ];

        return $data;
    }

    public function getFollowUpPhoto($id)
    {
        $photos = $this->followupPhoto->where('follow_up_id', $id)->where('st', 1)->get();
        foreach ($photos as $value) {
            $value->photo = (env('APP_ENV', 'production')=='production') ? self::IMG_DIR_PROD.$value->photo : self::IMG_DIR_DEV.$value->photo;
        }
        return $photos;
    }

    public function getTotalFollowUpProduct($id)
    {
        return $this->followupProduct->where('follow_up_id', $id)->where('merk_hpl_id', 1)->count();
    }

    public function getSalesDetailById($id)
    {
        $product = $this->followupProduct
                        ->with([
                                'product_carta' => function($q) { $q->select('follow_up_product_id', 'product_id', 'quantity', 'points'); },
                                'product_carta.master_product' => function($q) { $q->select('product_id', 'product_code', 'product_alias', 'sales_material_group3_desc'); }
                            ])
                        ->where('follow_up_id', $id)
                        ->where('merk_hpl_id', 1)
                        ->first();
        $product->order_receipt = (env('APP_ENV', 'production')=='production') ? self::IMG_DIR_PROD.$product->order_receipt : self::IMG_DIR_DEV.$product->order_receipt;
        foreach ($product->product_carta as $value) {
            $value->uom = "";
            if(substr($value->master_product->product_code, 0, 4)=='S0CT')
                $value->uom = 'SHT';
            if(substr($value->master_product->product_code, 0, 4)=='SECT')
                $value->uom = 'M';
        }
        return $product;
    }

    public function followupHistoryJoin($id, $start_date, $end_date, $start, $length, $order)
    {
        $query = "
            SELECT 
                'leads' as category,
                a.id, 
                a.follow_up_date,
                b.type,
                c.status,
                a.customer_feedback,
                a.hpl,
                a.edging,
                b.follow_up_status,
                a.created_at
            FROM mcs_follow_up as a
            LEFT JOIN mcs_sales_networking_visit_type b on a.follow_up_type_id=b.sales_networking_visit_type_id
            LEFT JOIN mcs_sales_networking_visit_lead_status c on a.follow_up_lead_status_id=c.sales_networking_visit_lead_status_id
            WHERE a.lead_id = ?
            AND st=1
            AND TO_DATE(a.follow_up_date, 'DD-MM-YYYY') >= ?
			AND TO_DATE(a.follow_up_date, 'DD-MM-YYYY') <= ?
            
            UNION ALL

            SELECT
                'account',
                a.sales_networking_visit_id, 
                a.visit_date,
                b.type,
                c.status,
                a.customer_feedback,
                a.hpl,
                a.edging,
                b.follow_up_status,
                a.created_at 
            FROM mcs_sales_networking_visit as a
            LEFT JOIN mcs_sales_networking_visit_type b on a.visit_type_id=b.sales_networking_visit_type_id
            LEFT JOIN mcs_sales_networking_visit_lead_status c on a.lead_status_id=c.sales_networking_visit_lead_status_id
            LEFT JOIN mcs_sales_networking d on a.sales_networking_id=d.sales_networking_id
            WHERE d.lead_id= ?
            AND a.st=1
            AND TO_DATE(visit_date, 'DD-MM-YYYY') >= ?
			AND TO_DATE(visit_date, 'DD-MM-YYYY') <= ?
        ";

        $followup = DB::select($query, [$id, $start_date, $end_date, $id, $start_date, $end_date]);
        
        $data = [
            'recordsTotal'    => count($followup),
            'recordsFiltered' => count($followup),
            'data'	          => $followup,
        ];

        return $data;
    }
}
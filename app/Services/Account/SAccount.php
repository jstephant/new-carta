<?php

namespace App\Services\Account;

use App\Models\AccountContact;
use App\Models\AccountContactEmail;
use App\Models\AccountContactPhone;
use App\Models\AccountFollowup;
use App\Models\AccountFollowupProduct;
use App\Models\AccountFollowupProductCarta;
use App\Models\CustomerAccount;
use App\Services\Account\IAccount;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SAccount implements IAccount
{
    private $customerAccount;
    private $accountContact;
    private $accountContactPhone;
    private $accountContactEmail;
    private $accountFollowup;
    private $accountFollowupProduct;
    private $accountFollowupProductCarta;

    public function __construct(
            CustomerAccount $customerAccount, 
            AccountContact $accountContact,
            AccountContactPhone $accountContactPhone,
            AccountContactEmail $accountContactEmail,
            AccountFollowup $accountFollowup,
            AccountFollowupProduct $accountFollowupProduct,
            AccountFollowupProductCarta $accountFollowupProductCarta
        )
    {
        $this->customerAccount = $customerAccount;
        $this->accountContact = $accountContact;
        $this->accountContactPhone = $accountContactPhone;
        $this->accountContactEmail = $accountContactEmail;
        $this->accountFollowup = $accountFollowup;
        $this->accountFollowupProduct = $accountFollowupProduct;
        $this->accountFollowupProductCarta = $accountFollowupProductCarta;
    }

    public function accountSelect()
    {
        return $this->customerAccount->select(
                                                'mcs_sales_networking.*',
                                                'a.category as customer_category',
                                                'b.name as teritory_name',
                                                'c.name as village_name',
                                                'e.name as city_name',
                                                'f.name as province_name'
                                            )
                                     ->leftjoin('mcs_customer_category as a', 'mcs_sales_networking.customer_category_id', 'a.customer_category_id')
                                     ->leftjoin('mcs_teritory as b', 'mcs_sales_networking.teritory_id', 'b.teritory_id')
                                     ->leftjoin('mcs_village as c', 'mcs_sales_networking.village_id', 'c.village_id')
                                     ->leftjoin('mcs_subdistrict as d', 'c.subdistrict_id', 'd.subdistrict_id')
                                     ->leftjoin('mcs_city as e', 'd.city_id', 'e.city_id')
                                     ->leftjoin('mcs_province as f', 'e.province_id', 'f.province_id');                                     
    }

    public function get($start_date, $end_date, $dob_from='', $dob_to='', $keyword='', $start, $length, $order)
    {
        $accounts = $this->customerAccount
                        ->with([
                                'customer_category' => function($q){ $q->select('customer_category_id', 'category'); },
                                'teritory'          => function($q){ $q->select('teritory_id', 'name'); },
                                'village'           => function($q){ $q->select('village_id', 'name', 'subdistrict_id')->with(['subdistrict', 'subdistrict.city', 'subdistrict.city.province']); },
                                'data_source'       => function($q){ $q->select('data_source_id', 'name'); },
                                'contact_one'       => function($q){ $q->select('sales_networking_id', 'sales_networking_contact_id')->with(['contact_phone_one', 'contact_email_one']);}
                            ])
                        ->select('*')
                        ->where('st', 1)
                        ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);

        if($dob_from && $dob_to)
        {
            $accounts = $accounts->whereBetween(DB::raw('DATE(customer_dob)'), [$dob_from, $dob_to]);
        }

        if($keyword)
        {
            $accounts = $accounts->where('customer_name', 'like', '%'.$keyword.'%')
                                 ->orwhereHas('contact_one.contact_phone_one', function($q) use($keyword) { $q->where('phone', 'like', '%'.$keyword.'%'); })
                                 ->orwhereHas('contact_one.contact_email_one', function($q) use($keyword) { $q->where('email', 'like', '%'.$keyword.'%'); });
        }

        $count = $accounts->count();

        if($length!=-1) {
            $accounts = $accounts->offset($start)->limit($length);
        } 
        
        $accounts = $accounts->get();
        
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $accounts->toArray(),
        ];

        return $data;
    }

    public function findById($id)
    {
        return $this->customerAccount
                        ->with([
                                'customer_category' => function($q){ $q->select('customer_category_id', 'category'); },
                                'teritory'          => function($q){ $q->select('teritory_id', 'name'); },
                                'village'           => function($q){ $q->select('village_id', 'name', 'subdistrict_id')->with(['subdistrict.city.province.province_teritory']); },
                                'data_source'       => function($q){ $q->select('data_source_id', 'name'); },
                                'contact_one'       => function($q){ $q->select('sales_networking_id', 'sales_networking_contact_id')->with(['contact_phone_one', 'contact_email_one']);},
                                'assigned_to'       => function($q){ $q->select('nik', 'name'); },
                                'leads'             => function($q){ $q->select('id', 'status_id'); },
                                'leads.status'      => function($q){ $q->select('id', 'name'); }
                            ])
                        ->where('sales_networking_id', $id)
                        ->first();
    }

    public function update($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed'
        );

        try {
            DB::beginTransaction();
            $new = CustomerAccount::where('sales_networking_id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function createContact($input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $new_id = DB::table('mcs_sales_networking_contact')->insertGetId($input, 'mcs_sales_networking_contact_id');
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $new_id;
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function updateContact($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed'
        );

        try {
            DB::beginTransaction();
            $updated = AccountContact::where('sales_networking_contact_id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function createContactPhone($input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $new_id = DB::table('mcs_sales_networking_contact_phone')->insertGetId($input, 'mcs_sales_networking_contact_phone_id');
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $new_id;
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function updateContactPhone($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed'
        );

        try {
            DB::beginTransaction();
            $updated = AccountContactPhone::where('sales_networking_contact_phone_id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function createContactEmail($input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $new_id = DB::table('mcs_sales_networking_contact_email')->insertGetId($input, 'mcs_sales_networking_contact_email_id');
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $new_id;
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function updateContactEmail($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => 'failed'
        );

        try {
            DB::beginTransaction();
            $updated = AccountContactEmail::where('sales_networking_contact_email_id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        return $data;
    }

    public function getListSales($id, $start_date, $end_date, $start, $length, $order)
    {
        $products = $this->accountFollowupProduct
                         ->with(['account_follow_up',
                                'product_carta' => function($q) { $q->select('sales_networking_visit_product_id', DB::raw('SUM(points) as points'))->groupby('sales_networking_visit_product_id'); }
                            ])
                         ->whereHas('account_follow_up', function($q) use($id) { $q->where('sales_networking_id', $id)->where('st', 1); })
                         ->whereBetween(DB::raw("DATE(order_date)"), [$start_date, $end_date])
                         ->where('merk_hpl_id', 1)
                         ->where('approval_status', 2);

        $count = $products->count();

        if($length!=-1) {
            $products = $products->offset($start)->limit($length);
        } 
        
        $products = $products->get();
        
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $products->toArray(),
        ];

        return $data;
    }
}
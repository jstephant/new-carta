<?php

namespace App\Services\Leads;

use App\Models\CustomerAccount;
use App\Models\FollowUp;
use App\Models\LeadProduct;
use App\Models\Leads;
use App\Models\LogAssignedLead;
use App\Services\Leads\ILeads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SLeads implements ILeads
{
    private $leads;
    private $leadProduct;
    private $followUp;
    private $customerAccount;
    private $logAssignedLead;

    public function __construct(
        Leads $leads, 
        LeadProduct $leadProduct, 
        FollowUp $followUp,
        CustomerAccount $customerAccount,
        LogAssignedLead $logAssignedLead
    )
    {
        $this->leads = $leads;
        $this->leadProduct = $leadProduct;
        $this->followUp = $followUp;
        $this->customerAccount = $customerAccount;
        $this->logAssignedLead = $logAssignedLead;
    }

    public function leadSelect2()
    {
        return $this->leads
                    ->with([
                            'customer_category' => function($q){ $q->select('customer_category_id', 'category'); },
                            'contact_title'     => function($q){ $q->select('contact_title_id', 'name'); },
                            'occupation'        => function($q){ $q->select('id', 'name'); },
                            'position'          => function($q){ $q->select('position_id', 'name'); },
                            'teritory'          => function($q){ $q->select('teritory_id', 'name'); },
                            'province'          => function($q){ $q->select('province_id', 'name'); },
                            'city'              => function($q){ $q->select('city_id', 'name'); },
                            'subdistrict'       => function($q){ $q->select('subdistrict_id', 'name'); },
                            'village'           => function($q){ $q->select('village_id', 'name'); },
                            'average_hpl'       => function($q){ $q->select('id', 'name'); },
                            'data_source'       => function($q){ $q->select('data_source_id', 'name'); },
                            'assigned_to'       => function($q){ $q->select('nik', 'name'); },
                            'created_by'        => function($q){ $q->select('nik', 'name'); },
                            'updated_by'        => function($q){ $q->select('nik', 'name'); },
                            'status'            => function($q){ $q->select('id', 'name'); },
                        ])
                    ->select('*')
                    ->get();
    }
    
    public function leadSelect()
    {
        return $this->leads->select(
                    'mcs_leads.*', 
                    'mcs_customer_category.category as customer_category',
                    'mcs_contact_title.name as contact_title',
                    'mcs_occupation.name as occupation_name', 
                    'mcs_position.name as position_name',
                    'mcs_teritory.name as teritory_name',
                    'mcs_province.name as province_name',
                    'mcs_city.name as city_name',
                    'mcs_subdistrict.name as subdistrict_name',
                    'mcs_village.name as village_name',
                    'mcs_avg_hpl.name as avg_hpl_usage',
                    'mcs_data_source.name as data_source_name',
                    'u.name as assigned_to_name',
                    'u2.name as created_by',
                    'u3.name as updated_by',
                    'mcs_lead_status.name as status_name',
                    'mcs_sales_networking.created_at as account_conversion_date',
                    'mcs_brand.name as brand_name'
                )
            ->leftjoin('mcs_customer_category', 'mcs_leads.customer_category_id', 'mcs_customer_category.customer_category_id')
            ->leftjoin('mcs_contact_title', 'mcs_leads.contact_title_id', 'mcs_contact_title.contact_title_id')
            ->leftjoin('mcs_occupation', 'mcs_leads.occupation_id', 'mcs_occupation.id')
            ->leftjoin('mcs_position', 'mcs_leads.position_id', 'mcs_position.position_id')
            ->leftjoin('mcs_teritory', 'mcs_leads.teritory_id', 'mcs_teritory.teritory_id')
            ->leftjoin('mcs_province', 'mcs_leads.province_id', 'mcs_province.province_id')
            ->leftjoin('mcs_city', 'mcs_leads.city_id', 'mcs_city.city_id')
            ->leftjoin('mcs_subdistrict', 'mcs_leads.sub_district_id', 'mcs_subdistrict.subdistrict_id')
            ->leftjoin('mcs_village', 'mcs_leads.village_id', 'mcs_village.village_id')
            ->leftjoin('mcs_avg_hpl', 'mcs_leads.average_hpl_id', 'mcs_avg_hpl.id')
            ->leftjoin('mcs_data_source', 'mcs_leads.data_source_id', 'mcs_data_source.data_source_id')
            ->leftjoin('mcs_user as u', 'mcs_leads.assigned_to', 'u.nik')
            ->leftjoin('mcs_user as u2', 'mcs_leads.created_by', 'u2.nik')
            ->leftjoin('mcs_user as u3', 'mcs_leads.updated_by', 'u3.nik')
            ->leftjoin('mcs_lead_status', 'mcs_leads.status_id', 'mcs_lead_status.id')
            ->leftjoin('mcs_sales_networking', function($q) {
                $q->on('mcs_leads.id', '=', 'mcs_sales_networking.lead_id')
                  ->where('mcs_sales_networking.st', 1);
            })
            ->leftjoin('mcs_brand', 'mcs_brand.id', 'mcs_leads.brand_id');
    }

    public function create($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $leads = Leads::create($input);
            $id = $leads->id;
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $id;
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function update($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $leads = Leads::where('id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    public function delete($id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $leads = Leads::where('id', $id)->update(['st' => 0]);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    public function get($start_date=null, $end_date=null, $assigned_to=null, $status=null, $contact_name=null, $start, $length, $order)
    {
        $leads = $this->leadSelect();
        $leads = $leads->where('mcs_leads.st', 1)
                       ->whereBetween(DB::raw('DATE(mcs_leads.created_at)'), [$start_date, $end_date]);

        if($assigned_to)
        {
            $leads = $leads->where('assigned_to', $assigned_to);
        }

        if($status)
        {
            $leads = $leads->where('status_id', $status);
        }

        if($contact_name)
        {
            $leads = $leads->whereraw("lower(contact_name) like ? ", ['%'.strtolower($contact_name).'%']);
        }

        $count = $leads->count();

        if($length!=-1) {
            $leads = $leads->offset($start)->limit($length);
        } 
        
        $leads = $leads->get();
        foreach ($leads as $value) {
            $value->first_followup = $this->getFollowUpDate($value->id, 'asc');
            $value->last_followup = $this->getFollowUpDate($value->id, 'desc');
            $value->total_followup = $this->getTotalFollowUp($value->id);

            $value->assigned_by = '';
            $value->assigned_by_name = '';
            $value->assigned_at = '';

            $log_assigned = $this->findLogAssignedTo($value->id);
            if(count($log_assigned)>0)
            {
                $assigned = $log_assigned[0];
                $value->assigned_by = $assigned->created_by;
                $value->assigned_by_name = ($assigned->assigned_by) ? $assigned->assigned_by->name : '';     
                $value->assigned_at = date('Y-m-d', strtotime($assigned->created_at));
            }
        }
        
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $leads->toArray(),
        ];

        return $data;
    }

    public function findById($id)
    {
        $leads = $this->leadSelect();
        return $leads->where('mcs_leads.id', $id)->first();
    }

    public function createProduct($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
        );

        try {
            DB::beginTransaction();
            $leads = DB::table('mcs_lead_product')->insert($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function updateProduct($lead_id, $product_id, $qty)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $leads = LeadProduct::where('lead_id', $lead_id)->where('product_id', $product_id);
            $leads->qty = $qty;
            $leads->save();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteProductAll($lead_id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $leads = LeadProduct::where('lead_id', $lead_id);
            $leads->delete();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteProduct($lead_id, $product_id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $leads = LeadProduct::where('lead_id', $lead_id)->where('product_id', $product_id);
            $leads->delete();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;
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

    public function findAccountByLeadId($id)
    {
        $account = $this->customerAccount
                        ->with([
                                'customer_category' => function($q){ $q->select('customer_category_id', 'category'); },
                                'teritory'          => function($q){ $q->select('teritory_id', 'name'); },
                                'village'           => function($q){ $q->select('village_id', 'name', 'subdistrict_id')->with(['subdistrict', 'subdistrict.city', 'subdistrict.city.province']); },
                                'data_source'       => function($q){ $q->select('data_source_id', 'name'); },
                                'contact_one'       => function($q){ $q->select('sales_networking_id', 'sales_networking_contact_id')->with(['contact_phone_one', 'contact_email_one']);}
                            ])
                        ->select('*')
                        ->where('lead_id', $id)
                        ->where('st', 1)
                        ->first();

        return $account;
    }

    public function findDuplicateContactPhone($contact_phone)
    {
        $duplicate = 1;
        $leads = $this->leads->where('contact_phone', $contact_phone)->first();
        if(!$leads)
        {
            $account = $this->customerAccount
                        ->with('contact_one.contact_phone_one')
                        ->where('st', 1)
                        ->whereHas('contact_one.contact_phone_one', function($q) use($contact_phone) {
                            $q->where('phone', $contact_phone);
                        })
                        ->first();
            if(!$account)
            {
                $duplicate = null;
            }
        }

        return $duplicate;
    }

    public function findLogAssignedTo($lead_id, $all = false)
    {
        $data = $this->logAssignedLead->where('lead_id', $lead_id)->orderby('id', 'desc');
        if(!$all)
        {
            $data = $data->limit(1);
        }
        return $data->get();
    }
    
    public function createLogAssignedTo($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $leads = LogAssignedLead::create($input);
            $id = $leads->id;
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $id;
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;    
    }
}
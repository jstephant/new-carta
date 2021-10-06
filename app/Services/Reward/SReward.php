<?php

namespace App\Services\Reward;

use App\Models\AccountFollowup;
use App\Models\AccountFollowupProduct;
use App\Models\CustomerAccount;
use App\Models\CustomerReward;
use App\Models\CustomerRewardItem;
use App\Models\CustomerRewardStatus;
use App\Models\FollowUp;
use App\Models\FollowupProduct;
use App\Models\Reward;
use App\Models\RewardItem;
use App\Models\RewardItemSegmen;
use App\Models\RewardProduct;
use App\Services\Reward\IReward;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SReward implements IReward
{
    private $customerReward;
    private $customerRewardItem;
    private $customerRewardStatus;
    private $accountFollowup;
    private $accountFollowupProduct;
    private $followUp;
    private $followupProduct;
    private $reward;
    private $rewardItem;
    private $rewardItemSegmen;
    private $rewardProduct;

    public function __construct(
        CustomerReward $customerReward, 
        CustomerRewardItem $customerRewardItem,
        CustomerRewardStatus $customerRewardStatus,
        AccountFollowup $accountFollowup,
        AccountFollowupProduct $accountFollowupProduct,
        FollowUp $followUp,
        FollowupProduct $followupProduct,
        Reward $reward,
        RewardItem $rewardItem,
        RewardItemSegmen $rewardItemSegmen,
        RewardProduct $rewardProduct
    )
    {
        $this->customerReward = $customerReward;
        $this->customerRewardItem = $customerRewardItem;
        $this->customerRewardStatus = $customerRewardStatus;
        $this->accountFollowup = $accountFollowup;
        $this->accountFollowupProduct = $accountFollowupProduct;
        $this->followUp = $followUp;
        $this->followupProduct = $followupProduct;
        $this->reward = $reward;
        $this->rewardProduct = $rewardProduct;
        $this->rewardItem = $rewardItem;
        $this->rewardItemSegmen = $rewardItemSegmen;

    }

    public function getCustomerRewards($followup_start, $followup_to, $purchase_start = '', $purchase_to = '', $type = '', $area = '', $assigned_to = '', $name = '', $start, $length, $order)
    {
        $rewards = $this->customerReward
                        ->with(['customer_account'                   => function($q) { $q->select('sales_networking_id', 'nik', 'customer_name', 'customer_category_id', 'teritory_id', 'lead_id', 'points'); },
                                'customer_account.teritory'          => function($q) { $q->select('teritory_id', 'name'); },
                                'customer_account.customer_category' => function($q) { $q->select('customer_category_id', 'category'); },
                                'customer_account.assigned_to'       => function($q) { $q->select('nik', 'name'); },
                                'customer_reward_status',
                                'request_user'                       => function($q) { $q->select('nik', 'name'); }
                            ]);
        if($followup_start && $followup_to)
        {
            $rewards = $rewards->whereHas('customer_account.account_follow_up', function($q) use($followup_start, $followup_to){
                                            $q->whereBetween(DB::raw("TO_DATE(visit_date, 'DD-MM-YYYY')"), [$followup_start, $followup_to]);
                                });
        }

        if($purchase_start && $purchase_to)
        {
            $rewards = $rewards->whereHas('customer_account.account_follow_up.product', function($q) use($purchase_start, $purchase_to) {
                                            $q->whereBetween(DB::raw('DATE(order_date)'), [$purchase_start, $purchase_to]);
                                });
        }

        if($type)
        {
            $rewards = $rewards->whereHas('customer_account', function($q) use($type) { $q->where('customer_category_id', $type); });
        }

        if($area)
        {
            $rewards = $rewards->whereHas('customer_account', function($q) use($area) { $q->where('teritory_id', $area); });
        }

        if($assigned_to)
        {
            $rewards = $rewards->whereHas('customer_account', function($q) use($assigned_to) { $q->where('nik', $assigned_to); });
        }

        if($name)
        {
            $rewards = $rewards->whereHas('customer_account', function($q) use($name) { $q->where('customer_name', 'like', '%'.$name.'%'); });
        }

        $count = $rewards->count();

        if($length!=-1) {
            $rewards = $rewards->offset($start)->limit($length);
        } 
        
        $rewards = $rewards->get();
        foreach ($rewards as $value) {
            $value->latest_purchased = $this->getLastPurchasedDate($value->sales_networking_id);
            $value->latest_follow_up = $this->getLastFollowUpDate($value->sales_networking_id);
            $value->total_follow_up = $this->getTotalFollowUp($value->sales_networking_id);
        }

        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $rewards->toArray(),
        ];
        
        return $data;
    }

    public function findCustomerRewardById($id)
    {
        $reward = $this->customerReward
                        ->with(['customer_account'                   => function($q) { $q->select('sales_networking_id', 'nik', 'customer_name', 'customer_category_id', 'teritory_id', 'lead_id', 'points'); },
                                'customer_account.teritory'          => function($q) { $q->select('teritory_id', 'name'); },
                                'customer_account.customer_category' => function($q) { $q->select('customer_category_id', 'category'); },
                                'customer_account.assigned_to'       => function($q) { $q->select('nik', 'name'); },
                                'customer_reward_status',
                                'request_user'                       => function($q) { $q->select('nik', 'name'); },
                                'customer_reward_item',
                                'customer_reward_item.reward_item'
                            ])
                        ->where('customer_reward_id', $id)
                        ->first();
        $reward->latest_purchased = $this->getLastPurchasedDate($reward->sales_networking_id);
        $reward->latest_follow_up = $this->getLastFollowUpDate($reward->sales_networking_id);
        $reward->total_follow_up = $this->getTotalFollowUp($reward->sales_networking_id);
        return $reward;
    }

    public function getLastPurchasedDate($sales_networking_id)
    {
        $purchase =  $this->accountFollowupProduct
                          ->select('order_date')
                          ->whereHas('account_follow_up', function($q) use($sales_networking_id) {
                              $q->where('sales_networking_id', $sales_networking_id);
                          })
                          ->wherenotnull('order_date')
                          ->orderby('order_date', 'desc')
                          ->first();

        return ($purchase) ? date('d-m-Y', strtotime($purchase->order_date)) : null;
    }

    public function getLastFollowUpDate($sales_networking_id)
    {
        $followup = $this->accountFollowup
                        ->select('visit_date')
                        ->where('sales_networking_id', $sales_networking_id)
                        ->where('st', 1)
                        ->wherenotnull('visit_date')
                        ->orderby('visit_date', 'desc')
                        ->first();

        return ($followup) ? $followup->visit_date : null;
    }

    public function getTotalFollowUp($sales_networking_id)
    {
        return $this->accountFollowup->where('sales_networking_id', $sales_networking_id)->where('st', 1)->get()->count();
    }

    public function getRewards($start, $length, $order)
    {
        $rewards = $this->reward->with([
                                        'customer_category', 
                                        'reward_product.master_product' => function($q) { $q->select('product_id', 'product_code', 'product_name', 'sales_material_group3_desc'); }
                                    ])
                                ->where('st', 1);

        $count = $rewards->count();

        if($length!=-1) {
            $rewards = $rewards->offset($start)->limit($length);
        } 
        
        $rewards = $rewards->get();

        foreach ($rewards as $value) {
            $pluck_model = array();
            $pluck_sku = array();
            foreach ($value->reward_product as $value2) {
                $pluck_model[] = $value2->master_product->sales_material_group3_desc;
                $pluck_sku[] = $value2->master_product->product_code;
            }
            $value->model = implode(', ', $pluck_model);
            $value->sku = implode(', ', $pluck_sku);
        }
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $rewards->toArray(),
        ];
        
        return $data;
    }

    public function createRewards($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $next_id = DB::select("select nextval('mcs_reward_id')");
            $id = intval($next_id['0']->nextval);
            $input['reward_id'] = $id;
            $new = Reward::create($input);
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

    public function deleteRewards($id)
    {
        $data = array(
            'status'  => false,
            'message' => '',
        );

        try {
            DB::beginTransaction();
            $deleted = Reward::where('reward_id', $id)->first();
            $deleted->st = 0;
            $deleted->save();
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

    public function getRewardItems($start, $length, $order)
    {
        $reward_items = $this->rewardItem->with(['reward_item_segmen.customer_category'])
                                         ->where('st', 1);

        $count = $reward_items->count();

        if($length!=-1) {
            $reward_items = $reward_items->offset($start)->limit($length);
        } 
        
        $reward_items = $reward_items->get();

        foreach ($reward_items as $value) {
            $pluck = array();
            foreach ($value->reward_item_segmen as $value2) {
                $pluck[] = $value2->customer_category->category;
            }
            $value->segmentation = implode(', ', $pluck);
        }

        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $reward_items->toArray(),
        ];
        
        return $data;
    }

    public function createRewardItems($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $next_id = DB::select("select nextval('mcs_reward_item_id')");
            $id = intval($next_id['0']->nextval);
            $input['reward_item_id'] = $id;
            $new = RewardItem::create($input);
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

    public function deleteRewardItems($id)
    {
        $data = array(
            'status'  => false,
            'message' => '',
        );

        try {
            DB::beginTransaction();
            $deleted = RewardItem::where('reward_item_id', $id)->first();
            $deleted->st = 0;
            $deleted->save();
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

    public function createRewardProduct($input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $new = RewardProduct::create($input);
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

    public function deleteRewardProductAll($reward_id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $delete = RewardProduct::where('reward_id', $reward_id)->delete();
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

    public function createRewardItemSegmentation($input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $new = RewardItemSegmen::create($input);
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

    public function deleteRewardItemSegmentationAll($reward_item_id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $delete = RewardItemSegmen::where('reward_item_id', $reward_item_id)->delete();
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

    public function getListSalesApproval($start_date, $end_date, $status='', $name='', $start, $length, $order)
    {
        $sales = $this->accountFollowupProduct
                      ->with([
                            'account_follow_up'                  => function($q) { $q->select('sales_networking_visit_id', 'sales_networking_id', 'st', 'hpl', 'edging'); },
                            'account_follow_up.customer_account' => function($q) { $q->select('sales_networking_id', 'nik', 'customer_name', 'customer_category_id', 'teritory_id', 'points')
                                                                                     ->with(['customer_category', 'teritory', 
                                                                                            'assigned_to' => function($q){ $q->select('nik', 'name'); }
                                                                                            ]);
                                                                                        },
                            'sales_approval_status'
                        ])
                      ->where('merk_hpl_id', 1)
                      ->whereBetween(DB::raw("DATE(order_date)"), [$start_date, $end_date])
                      ->whereHas('account_follow_up', function($q) { $q->where('st', 1); })
                      ->whereHas('account_follow_up.customer_account', function($q) { $q->where('st', 1); })
                      ->select('sales_networking_visit_product_id', 'merk_hpl_id', 'collect', 'order_date', 'approval_status', 'approval_date', 
                            'sales_networking_visit_id');
        
        if($status)
        {
            $sales = $sales->where('approval_status', $status);
        }

        if($name)
        {
            $sales = $sales->whereHas('customer_account', function($q) use($name) {$q->where('customer_name', 'like', '%'.$name.'%'); });
        }
        
        $count = $sales->count();

        if($length!=-1) {
            $sales = $sales->offset($start)->limit($length);
        } 
        
        $sales = $sales->get();
        
        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $sales->toArray(),
        ];

        return $data;
    }

    public function salesApproval($sales_networking_visit_id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );
        try {
            DB::beginTransaction();
            $sales_approval = AccountFollowupProduct::where('sales_networking_visit_id', $sales_networking_visit_id)
                                                    ->where('merk_hpl_id', 1)
                                                    ->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        // update point at sales_networking
        $sales = $this->accountFollowupProduct
                      ->with([
                            'account_follow_up'                  => function($q) { $q->select('sales_networking_visit_id', 'sales_networking_id', 'st', 'hpl', 'edging'); },
                            'account_follow_up.customer_account' => function($q) { $q->select('sales_networking_id', 'nik', 'customer_name', 'customer_category_id', 'teritory_id')
                                                                                     ->with(['customer_category', 'teritory', 
                                                                                            'assigned_to' => function($q){ $q->select('nik', 'name'); }
                                                                                            ]);
                                                                                        },
                            'sales_approval_status',
                            'product_carta' => function($q) { $q->select('sales_networking_visit_product_id', DB::raw('SUM(points) as points'))->groupby('sales_networking_visit_product_id'); }
                        ])
                      ->where('merk_hpl_id', 1)
                      ->where('sales_networking_visit_id', $sales_networking_visit_id)
                      ->whereHas('account_follow_up', function($q) { $q->where('st', 1); })
                      ->select('sales_networking_visit_product_id', 'merk_hpl_id', 'collect', 'order_date', 'approval_status', 'approval_date', 
                            'sales_networking_visit_id')
                      ->get();
        // return $sales;
        foreach ($sales as $value)
        {
            $total_point = 0;
            foreach($value->product_carta as $item)
            {
                $total_point = $total_point + $item->points;
            }

            $customer_account = CustomerAccount::whereHas('account_follow_up', function($q) use($sales_networking_visit_id){
                                                    $q->where('sales_networking_visit_id', $sales_networking_visit_id);
                                                })
                                                ->get();

            foreach ($customer_account as $value2) {
                $new_points = $value2->points + $total_point;
                try {
                    DB::beginTransaction();
                    $update_point = CustomerAccount::where('sales_networking_id', $value2->sales_networking_id)
                                                    ->update(['points' => $new_points]);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    Log::info($e->getMessage());
                }    
            }
        }
            
        return $data;
    }

    public function getRedeemHistory($id)
    {
        $redeems = $this->customerReward
                        ->with([
                                'customer_reward_item',
                                'customer_reward_item.reward_item'
                        ])
                        ->where('sales_networking_id', $id)
                        ->orderby('request_date', 'desc')
                        ->get();

        foreach ($redeems as $value) {
            $point = 0;
            foreach ($value->customer_reward_item as $value2) {
                $point += ($value2->quantity * $value2->points_per_qty);
            }
            $value->total_point = $point;
        }
        return $redeems;
    }

    public function approveRequest($id, $nik)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );
        try {
            DB::beginTransaction();
            $reward = CustomerReward::where('customer_reward_id', $id)->first();
            $reward->customer_reward_status_id = 2;
            $reward->approval_date = date('Y-m-d H:i:s');
            $reward->approval_by = $nik;
            $reward->save();
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

    public function rejectRequest($id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        // update status reject
        try {
            DB::beginTransaction();
            $reward = CustomerReward::where('customer_reward_id', $id)->first();
            $reward->customer_reward_status_id = 4;
            $reward->save();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
            Log::info($e->getMessage());
        }

        if($data['status'])
        {
            $data['status'] = false;
            $data['message'] = '';

            $reward_item = $this->customerReward->with('customer_reward_item')->where('customer_reward_id', $id)->first();
            $total_point = 0;
            foreach ($reward_item->customer_reward_item as $value) {
                $total_point += ($value->quantity * $value->points_per_qty);
            }

            try {
                DB::beginTransaction();
                $customer_account = CustomerAccount::where('sales_networking_id', $reward_item->sales_networking_id)->first();
                $customer_account->points = $customer_account->points + $total_point;
                $customer_account->save();
                DB::commit();
                $data['status'] = true;
                $data['message'] = 'OK';
            } catch (Exception $e) {
                DB::rollback();
                $data['message'] = $e->getMessage();
                Log::info($e->getMessage());
            }
        }
        return $data;
    }

    public function deliverRequest($id)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );
        try {
            DB::beginTransaction();
            $reward = CustomerReward::where('customer_reward_id', $id)->first();
            $reward->customer_reward_status_id = 3;
            $reward->delivery_date = date('Y-m-d H:i:s');
            $reward->save();
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
}
<?php

namespace App\Http\Controllers;

use App\Services\Account\SAccount;
use App\Services\FollowUp\SFollowUpAccount;
use App\Services\Reward\SReward;
use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class RewardsController extends Controller
{
    private $sGlobal;
    private $sReward;
    private $sUser;
    private $sAccount;
    private $sFollowUpAccount;

    public function __construct(
        SGlobal $sGlobal, 
        SReward $sReward, 
        SUser $sUser,
        SAccount $sAccount,
        SFollowUpAccount $sFollowUpAccount
    )
    {
        $this->sGlobal = $sGlobal;
        $this->sReward = $sReward;
        $this->sUser = $sUser;
        $this->sAccount = $sAccount;
        $this->sFollowUpAccount = $sFollowUpAccount;
    }

    public function index()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.customer_reward'));
        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();
        $sales_menu = $this->sGlobal->getSalesHistoryMenu();
        $redeem_menu = $this->sGlobal->getRedeemHistoryMenu();

        $customer_category = $this->sGlobal->getCustomerCategory();
        $teritory = $this->sGlobal->getTeritories();
        $assigned_to = $this->sUser->get();

        $data = array(
            'title'         => 'Carta - Customer Reward',
            'active_menu'   => 'Customer Reward',
            'access_from'   => 'reward',
            'category'      => $customer_category,
            'teritory'      => $teritory,
            'assigned_to'   => $assigned_to,
            'my_menu'       => collect($my_menu),
            'followup_menu' => collect($followup_menu),
            'sales_menu'    => collect($sales_menu),
            'redeem_menu'   => collect($redeem_menu),
            'url_data'      => '/reward/list',
            'url_redeem_history' => '/redeem-history/reward/detail/'
        );

        return $this->sGlobal->view('reward.index', $data);
    }

    public function listCustomerReward(Request $request)
    {
        $followup_start = ($request->followup_start) ? $this->sGlobal->convertDate($request->followup_start) : null;
        $followup_to = ($request->followup_to) ? $this->sGlobal->convertDate($request->followup_to) : null;
        $purchase_start = ($request->purchase_start) ? $this->sGlobal->convertDate($request->purchase_start) : null;
        $purchase_to = ($request->purchase_to) ? $this->sGlobal->convertDate($request->purchase_to) : null;
        $type = $request->type;
        $area = $request->area;
        $assigned_to = $request->assigned_to;
        $name = $request->name;

        $rewards = $this->sReward->getCustomerRewards($followup_start, $followup_to, $purchase_start, $purchase_to, 
                                        $type, $area, $assigned_to, $name, $request->start, $request->length, $request->order);
        $rewards['draw'] = $request->draw;
        
        return $rewards;
    }

    public function detail($id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();
        $sales_menu = $this->sGlobal->getSalesHistoryMenu();
        $redeem_menu = $this->sGlobal->getRedeemHistoryMenu();

        $account = $this->sAccount->findById($id);
        
        $data = array(
            'title'         => 'Carta - Detail Customer Reward',
            'active_menu'   => 'Detail Customer Reward',
            'reward'        => $account,
            'followup_menu' => collect($followup_menu),
            'sales_menu'    => collect($sales_menu),
            'redeem_menu'   => collect($redeem_menu),
            'url_redeem_history' => '/redeem-history/reward/detail/'
        );

        return $this->sGlobal->view('reward.detail', $data);
    }    

    public function manageReward()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.manage_reward'));
        $reward_item_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.manage_reward_item'));

        $data = array(
            'title'            => 'Carta - Manage Reward',
            'active_menu'      => 'Manage Reward',
            'my_menu'          => collect($my_menu),
            'reward_item_menu' => collect($reward_item_menu)
        );

        return $this->sGlobal->view('reward.manage-reward', $data);
    }

    public function createManageReward()
    {
        $customer_category = $this->sGlobal->getCustomerCategory();
        $products = $this->sGlobal->getProductByType('HPL', '');

        $data = array(
            'title'       => 'Carta - Manage Reward',
            'active_menu' => 'Create Manage Reward',
            'categories'  => $customer_category,
            'products'    => $products
        );

        return $this->sGlobal->view('reward.manage-reward-create', $data);
    }

    public function doCreateManageReward(Request $request)
    {
        $desc = $request->description;
        $customer_category_id = $request->customer_category;
        $qty = $request->quantity;
        $points = $request->points;
        $type = $request->type;
        $notes = $request->notes;
        $created_at = date('Y-m-d H:i:s');
        $created_by = $request->session()->get('nik');
        $products = $request->products;

        $input = array(
            'description'          => $desc,
            'customer_category_id' => $customer_category_id,
            'quantity'             => $qty,
            'points'               => $points,
            'type'                 => $type,
            'notes'                => $notes,
            'created_at'           => $created_at,
            'created_by'           => $created_by,
        );

        $new = $this->sReward->createRewards($input);
        if(!$new['status'])
        {
            alert()->error('Error', $new['message']);
            return redirect()->back();
        }

        $delete_product = $this->sReward->deleteRewardProductAll($new['id']);
        foreach ($products as $value) {
            $input_detail = array(
                'reward_id'  => $new['id'],
                'product_id' => $value
            );
            $new_detail = $this->sReward->createRewardProduct($input_detail);
        }

        alert()->success('Success', 'Reward has been saved');
        return redirect()->route('manage-reward');
    }

    public function doDeleteManageReward($id)
    {
        $deleted = $this->sReward->deleteRewards($id);
        if(!$deleted['status'])
        {
            alert()->error('Error', $deleted['message']);
            return redirect()->back();
        }
        alert()->success('Success', 'Reward has been saved');
        return redirect()->route('manage-reward');
    }

    public function findById($id)
    {
        $reward = $this->sReward->findCustomerRewardById($id);
        return response()->json($reward, 200);
    }

    public function listReward(Request $request)
    {
        $rewards = $this->sReward->getRewards($request->start, $request->length, $request->order);
        $rewards['draw'] = $request->draw;
        
        return $rewards;
    }

    public function manageRewardItem()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.manage_reward_item'));
        
        $data = array(
            'title'       => 'Carta - Manage Reward Item',
            'active_menu' => 'Manage Reward Item',
            'my_menu'     => collect($my_menu)
        );

        return $this->sGlobal->view('reward.manage-reward-item', $data);
    }

    public function listRewardItem(Request $request)
    {
        $rewards = $this->sReward->getRewardItems($request->start, $request->length, $request->order);
        $rewards['draw'] = $request->draw;
        
        return $rewards;
    }

    public function createManageRewardItem()
    {
        $customer_category = $this->sGlobal->getCustomerCategory();

        $data = array(
            'title'       => 'Carta - Manage Reward Item',
            'active_menu' => 'Create Manage Reward Item',
            'categories'  => $customer_category,
        );

        return $this->sGlobal->view('reward.manage-reward-item-create', $data);
    }

    public function doCreateManageRewardItem(Request $request)
    {
        $item_code = $request->item_code;
        $desc = $request->description;
        $points = $request->point;
        $notes = $request->notes;
        $customer_category = $request->customer_category;

        $input = array(
            'item_code'       => $item_code,
            'description'     => $desc,
            'points_required' => $points,
            'notes'           => $notes,
        );

        $new = $this->sReward->createRewardItems($input);
        if(!$new['status'])
        {
            alert()->error('Error', $new['message']);
            return redirect()->back();
        }

        $delete_segmen = $this->sReward->deleteRewardItemSegmentationAll($new['id']);
        foreach ($customer_category as $value) {
            $input_detail = array(
                'reward_item_id'       => $new['id'],
                'customer_category_id' => $value
            );
            $new_detail = $this->sReward->createRewardItemSegmentation($input_detail);
        }

        alert()->success('Success', 'Reward Item has been saved');
        return redirect()->route('manage-reward-item');
    }

    public function doDeleteManageRewardItem($id)
    {
        $deleted = $this->sReward->deleteRewardItems($id);
        if(!$deleted['status'])
        {
            alert()->error('Error', $deleted['message']);
            return redirect()->back();
        }
        alert()->success('Success', 'Reward has been saved');
        return redirect()->route('manage-reward-item');
    }

    public function salesApproval()
    {
        $status = $this->sGlobal->getApprovalStatus();
        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.sales_approval'));

        $data = array(
            'title'            => 'Carta - Sales Approval',
            'active_menu'      => 'Sales Approval',
            'access_from'      => 'reward',
            'status'           => $status,
            'access_from'      => 'reward',
            'my_menu'          => collect($my_menu),
            'url_data'         => '/sales-history/reward/list',
            'url_sales_detail' => '/sales-detail/reward/data/',
        );

        return $this->sGlobal->view('reward.sales-approval', $data);
    }

    public function listSalesApproval(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;
        $status = $request->status;
        $name = $request->name;

        $sales = $this->sReward->getListSalesApproval($start_date, $end_date, $status, $name, $request->start, $request->length, $request->order);
        
        $sales['draw'] = $request->draw;
        
        return $sales;
    }

    public function doSalesApproval(Request $request)
    {
        $sales_networking_visit_id = $request->sales_id;
        $action = $request->action;
        $approval_by = $request->session()->get('nik');
        if($action=='approve')
        {
            $input = array(
                'approval_status' => 2, 
                'approval_date'   => date('Y-m-d H:i:s'),
                'approval_by'     => $approval_by,
            );
            $sales = $this->sReward->salesApproval($sales_networking_visit_id, $input);
        }
        
        alert()->success('Success', 'Sales has been approved');
        return redirect()->route('sales-approval');
    }

    public function listRedeemHistory($access_from, $id)
    {
        $redeems = $this->sReward->getRedeemHistory($id);
        return response()->json($redeems, 200);
    }

    public function approvalRedeem(Request $request)
    {
        $id = $request->customer_reward_id;
        $active_user = $request->session()->get('nik');
        $action = $request->action;
        
        if($action=='approve')
        {
            $approved = $this->sReward->approveRequest($id, $active_user);
            if(!$approved['status'])
            {
                alert()->error('Error', $approved['message']);
                return redirect()->back();
            } else 
            {
                alert()->success('Success', 'Redeem request has been approved');
                return redirect()->route('customer-reward');
            }

        } elseif($action=='reject')
        {
            $rejected = $this->sReward->rejectRequest($id);
            if(!$rejected['status'])
            {
                alert()->error('Error', $rejected['message']);
                return redirect()->back();
            } else 
            {
                alert()->success('Success', 'Redeem request has been rejected');
                return redirect()->route('customer-reward');
            }
        }
    }

    public function deliverRequest($id)
    {
        $delivery = $this->sReward->deliverRequest($id);
        
        if(!$delivery['status'])
        {
            alert()->error('Error', $delivery['message']);
            return redirect()->back();
        }

        alert()->success('Success', 'Item has been delivered');
        return redirect()->route('customer-reward');
    }
}

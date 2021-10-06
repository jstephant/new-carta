<?php

namespace App\Http\Controllers;

use App\Services\Account\SAccount;
use App\Services\FollowUp\SFollowUpAccount;
use App\Services\FollowUp\SFollowUpLeads;
use App\Services\Leads\SLeads;
use App\Services\SGlobal;
use Illuminate\Http\Request;
use Session;

class FollowupController extends Controller
{
    private $sGlobal;
    private $sFollowUpLeads;
    private $sFollowUpAccount;
    private $sLeads;
    private $sAccount;

    public function __construct(
        SGlobal $sGlobal,
        SFollowUpLeads $sFollowUpLeads,
        SFollowUpAccount $sFollowUpAccount,
        SLeads $sLeads,
        SAccount $sAccount
    )
    {
        $this->sGlobal = $sGlobal;
        $this->sFollowUpLeads = $sFollowUpLeads;
        $this->sFollowUpAccount = $sFollowUpAccount;
        $this->sLeads = $sLeads;
        $this->sAccount = $sAccount;
    }

    public function followup($access_from, $id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }
        
        if($access_from=='leads')
        {
            $header = $this->sLeads->findById($id);
        } else {
            $header = $this->sAccount->findById($id);
        }

        $url_data = '/followup/'.$access_from.'/list';
        $url_sales_detail = '/sales-detail/'.$access_from.'/data/';

        $data = array(
            'title'            => 'Carta - Follow Up ',
            'active_menu'      => 'Follow Up History',
            'header'           => $header,
            'access_from'      => $access_from,
            'url_data'         => $url_data,
            'url_sales_detail' => $url_sales_detail
        );

        return $this->sGlobal->view('follow-up.index', $data);
    }

    public function listFollowup2(Request $request)
    {
        $id = $request->header_id;
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $follow_up = null;
        if($request->access_from=='leads')
            $follow_up = $this->sFollowUpLeads->getListFollowUp($id, $start_date, $end_date, $request->start, $request->length, $request->order);
        else
            $follow_up = $this->sFollowUpAccount->getListFollowUp($id, $start_date, $end_date, $request->start, $request->length, $request->order);

        $follow_up['draw'] = $request->draw;
        
        return $follow_up;
    }

    public function followupPhotos(Request $request)
    {
        $photos = null;
        if($request->access_from=='leads')
            $photos = $this->sFollowUpLeads->getFollowUpPhoto($request->id);
        else 
            $photos = $this->sFollowUpLeads->getFollowUpPhoto($request->id);
        return $photos;
    }

    public function listFollowup(Request $request)
    {
        $id = $request->header_id;
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $follow_up = null;
        if($request->access_from=='leads')
            $follow_up = $this->sFollowUpLeads->followupHistoryJoin($id, $start_date, $end_date, $request->start, $request->length, $request->order);
        else $follow_up = $this->sFollowUpAccount->followupHistoryJoin($id, $start_date, $end_date, $request->start, $request->length, $request->order);
        $follow_up['draw'] = $request->draw;
        
        return $follow_up;
    }
}

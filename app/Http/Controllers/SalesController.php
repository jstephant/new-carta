<?php

namespace App\Http\Controllers;

use App\Services\Account\SAccount;
use App\Services\FollowUp\SFollowUpAccount;
use App\Services\FollowUp\SFollowUpLeads;
use App\Services\SGlobal;
use Illuminate\Http\Request;
use Session;

class SalesController extends Controller
{
    private $sGlobal;
    private $sFollowUpLeads;
    private $sFollowUpAccount;
    private $sAccount;

    public function __construct(
        SGlobal $sGlobal,
        SFollowUpLeads $sFollowUpLeads,
        SFollowUpAccount $sFollowUpAccount,
        SAccount $sAccount
    )
    {
        $this->sGlobal = $sGlobal;
        $this->sFollowUpLeads = $sFollowUpLeads;
        $this->sFollowUpAccount = $sFollowUpAccount;
        $this->sAccount = $sAccount;
    }

    public function salesHistory($access_from, $id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }
        
        $header = $this->sAccount->findById($id);

        $data = array(
            'title'             => 'Carta - Sales History ',
            'active_menu'       => 'Sales History',
            'header'            => $header,
            'access_from'       => $access_from,
            'url_data'          => '/sales-history/'.$access_from.'/list',
            'url_sales_detail'  => '/sales-detail/'.$access_from.'/data/',
        );

        return $this->sGlobal->view('sales.history.index', $data);
    }

    public function listSales(Request $request)
    {
        $id = $request->id;
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;

        $sales = $this->sAccount->getListSales($id, $start_date, $end_date, $request->start, $request->length, $request->order);
        $sales['draw'] = $request->draw;
        
        return $sales;
    }

    public function salesDetail($access_from, $id)
    {
        if($access_from=='leads')
            $product = $this->sFollowUpLeads->getSalesDetailById($id);
        else
            $product = $this->sFollowUpAccount->getSalesDetailById($id);
        return response()->json($product, 200);
    }
}

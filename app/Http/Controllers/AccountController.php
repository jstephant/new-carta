<?php

namespace App\Http\Controllers;

use App\Services\Account\SAccount;
use App\Services\FollowUp\SFollowUpAccount;
use App\Services\SGlobal;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Config;

class AccountController extends Controller
{
    private $sGlobal;
    private $sAccount;
    private $sFollowUpAccount;

    public function __construct(
        SGlobal $sGlobal, 
        SAccount $sAccount, 
        SFollowUpAccount $sFollowUpAccount)
    {
        $this->sGlobal = $sGlobal;
        $this->sAccount = $sAccount;
        $this->sFollowUpAccount = $sFollowUpAccount;
    }

    public function index()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.customer_account'));
        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();
        $sales_menu = $this->sGlobal->getSalesHistoryMenu();
        $redeem_menu = $this->sGlobal->getRedeemHistoryMenu();

        $data = array(
            'title'              => 'Carta - Account',
            'active_menu'        => 'Customer Accounts',
            'my_menu'            => collect($my_menu),
            'followup_menu'      => collect($followup_menu),
            'sales_menu'         => collect($sales_menu),
            'redeem_menu'        => collect($redeem_menu),
            'url_redeem_history' => '/redeem-history/account/detail/'
        );

        return $this->sGlobal->view('account.index', $data);
    }

    public function getList(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;
        $dob_from = ($request->dob_from) ? $this->sGlobal->convertDate($request->dob_from) : null;
        $dob_to = ($request->dob_to) ? $this->sGlobal->convertDate($request->dob_to) : null;
        $keyword = $request->keyword;

        $accounts = $this->sAccount->get($start_date, $end_date, $dob_from, $dob_to, $keyword, $request->start, $request->length, $request->order);
        $accounts['draw'] = $request->draw;
        
        return $accounts;
    }

    public function edit($id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $account = $this->sAccount->findById($id);
        $categories = $this->sGlobal->getCustomerCategory();
        
        $account->phone = null;
        $account->email = null;
        if($account->contact_one)
        {
            $account->sales_networking_contact_id = $account->contact_one->sales_networking_contact_id;
            if($account->contact_one->contact_phone_one)
            {
                $account->sales_networking_contact_phone_id = $account->contact_one->contact_phone_one->sales_networking_contact_phone_id;
                $account->phone = $account->contact_one->contact_phone_one->phone;
            }

            if($account->contact_one->contact_email_one)
            {
                $account->sales_networking_contact_email_id = $account->contact_one->contact_email_one->sales_networking_contact_email_id;
                $account->email = $account->contact_one->contact_email_one->email;
            }
        }
        $teritories = $this->sGlobal->getTeritories();

        $province = null;
        $city = null;
        $subdistrict = null;
        $village = null;
        if($account->village)
        {
            $account->province_id = $account->village->subdistrict->city->province_id;
            $account->city_id = $account->village->subdistrict->city_id;
            $account->subdistrict_id = $account->village->subdistrict_id;

            $province = $this->sGlobal->getProvince($account->teritory_id);
            $city = $this->sGlobal->getCity($account->village->subdistrict->city->province_id);
            $subdistrict = $this->sGlobal->getSubDistrict($account->village->subdistrict->city_id);
            $village = $this->sGlobal->getVillage($account->village->subdistrict_id);
        }
        
        $data_sources = $this->sGlobal->getDataSource();

        $data = array(
            'title'         => 'Carta - Edit Account',
            'active_menu'   => 'Edit Customer Account',
            'account'       => $account,
            'categories'    => $categories,
            'teritories'    => $teritories,
            'provinces'     => $province,
            'cities'        => $city,
            'subdistricts'  => $subdistrict,
            'villages'      => $village,
            'data_sources'  => $data_sources,
        );

        return $this->sGlobal->view('account.edit', $data);
    }

    public function doUpdate(Request $request)
    {
        $account_id               = $request->sales_networking_id;
        $account_contact_id       = $request->sales_networking_contact_id;
        $account_contact_phone_id = $request->sales_networking_contact_phone_id;
        $account_contact_email_id = $request->sales_networking_contact_email_id;
        $customer_name            = $request->customer_name;
        $customer_category        = $request->customer_category;
        $contact_phone            = $request->contact_phone;
        $contact_email            = $request->contact_email;
        $customer_dob             = $request->customer_dob;
        $address                  = $request->address;
        $teritory_id              = $request->teritory;
        $village_id               = $request->village;
        $postal_code              = $request->postal_code;
        $data_source_id           = $request->data_source;
        $active_nik               = $request->session()->get('nik');

        $input = array(
            'customer_name'        => $customer_name,
            'customer_category_id' => ($customer_category) ? $customer_category : null,
            'customer_dob'         => ($customer_dob) ? $customer_dob : null,
            'detail_address'       => $address,
            'teritory_id'          => ($teritory_id) ? $teritory_id : null,
            'village_id'           => ($village_id) ? $village_id : null,
            'postal_code'          => ($postal_code) ? $postal_code : null,
            'data_source_id'       => ($data_source_id) ? $data_source_id : null,
            'updated_at'           => date('Y-m-d H:i:s'),
            'updated_by'           => ($active_nik) ? $active_nik : null
        );

        $created = $this->sAccount->update($account_id, $input);
        if(!$created['status'])
        {
            alert()->error('Error', $created['message']);
            return redirect()->back()->withInput($request->all());
        }

        // create or update contact
        if(!$account_contact_id)
        {
            $input_contact = array(
                'sales_networking_id' => $account_id,
                'st' => 1
            );

            $created_contact = $this->sAccount->createContact($input_contact);
            if(!$created_contact['status'])
            {
                alert()->error('Error', $created_contact['message']);
                return redirect()->back()->withInput($request->all());
            }
            $account_contact_id = $created_contact['id'];
        }

        if(!$account_contact_phone_id)
        {
            $input_contact_phone = array(
                'sales_networking_contact_id' => $account_contact_id,
                'phone' => $contact_phone,
                'phone_type_id' => 2
            );

            $created_phone = $this->sAccount->createContactPhone($input_contact_phone);
            if(!$created_phone['status'])
            {
                alert()->error('Error', $created_phone['message']);
                return redirect()->back()->withInput($request->all());
            }
        } else {
            $input_contact_phone = array(
                'phone' => $contact_phone,
                'phone_type_id' => 2
            );

            $updated_phone = $this->sAccount->updateContactPhone($account_contact_phone_id, $input_contact_phone);
            if(!$updated_phone['status'])
            {
                alert()->error('Error', $updated_phone['message']);
                return redirect()->back()->withInput($request->all());
            }
        }

        if(!$account_contact_email_id)
        {
            $input_contact_email = array(
                'sales_networking_contact_id' => $account_contact_id,
                'email' => $contact_email,
                'email_type_id' => 2
            );

            $created_email = $this->sAccount->createContactEmail($input_contact_email);
            if(!$created_email['status'])
            {
                alert()->error('Error', $created_email['message']);
                return redirect()->back()->withInput($request->all());
            }
        } else {
            $input_contact_email = array(
                'email' => $contact_email,
                'email_type_id' => 2
            );

            $updated_email = $this->sAccount->updateContactEmail($account_contact_email_id, $input_contact_email);
            if(!$updated_email['status'])
            {
                alert()->error('Error', $updated_email['message']);
                return redirect()->back()->withInput($request->all());
            }
        }

        alert()->success('Success', 'Data updated successfuly.');
        return redirect()->route('account');
    }

    public function detail($id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $account = $this->sAccount->findById($id);
        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();
        $sales_menu = $this->sGlobal->getSalesHistoryMenu();
        $redeem_menu = $this->sGlobal->getRedeemHistoryMenu();
        
        $data = array(
            'title'         => 'Carta - Detail Customer Accout',
            'active_menu'   => 'Detail Customer Account',
            'account'       => $account,
            'followup_menu'      => collect($followup_menu),
            'sales_menu'         => collect($sales_menu),
            'redeem_menu'        => collect($redeem_menu),
            'url_redeem_history' => '/redeem-history/account/detail/'
        );

        return $this->sGlobal->view('account.detail', $data);
    }
}

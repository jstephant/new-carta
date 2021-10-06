<?php

namespace App\Services;

use App\Models\ApprovalStatus;
use App\Models\AverageHPL;
use App\Models\Brand;
use App\Models\City;
use App\Models\ContactTitle;
use App\Models\CustomerCategory;
use App\Models\DataSource;
use App\Models\LeadStatus;
use App\Models\LogActivity;
use App\Models\Occupation;
use App\Models\Position;
use App\Models\Product;
use App\Models\Province;
use App\Models\Role;
use App\Models\SubDistrict;
use App\Models\Teritory;
use App\Models\Village;
use App\Services\IGlobal;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class SGlobal implements IGlobal
{
    private $teritory;
    private $province;
    private $city;
    private $sub_district;
    private $village;
    private $category;
    private $avg_hpl;
    private $occupation;
    private $contact_title;
    private $position;
    private $data_source;
    private $product;
    private $leadStatus;
    private $approvalStatus;
    private $logActivity;
    private $brand;
    private $roles;

    public function __construct(
            Teritory $teritory, 
            Province $province, 
            City $city, 
            SubDistrict $sub_district, 
            Village $village,
            CustomerCategory $category, 
            AverageHPL $avg_hpl, 
            Occupation $occupation, 
            ContactTitle $contact_title, 
            Position $position,
            DataSource $data_source, 
            Product $product, 
            LeadStatus $leadStatus,
            ApprovalStatus $approvalStatus,
            LogActivity $logActivity,
            Brand $brand,
            Role $roles
        )
    {
        $this->teritory = $teritory;
        $this->province = $province;
        $this->city = $city;
        $this->sub_district = $sub_district;
        $this->village = $village;
        $this->category = $category;
        $this->avg_hpl = $avg_hpl;
        $this->occupation = $occupation;
        $this->contact_title = $contact_title;
        $this->position = $position;
        $this->data_source = $data_source;
        $this->product = $product;
        $this->leadStatus = $leadStatus;
        $this->approvalStatus = $approvalStatus;
        $this->logActivity = $logActivity;
        $this->brand = $brand;
        $this->roles = $roles;
    }

    public function curlAPI($type, $url, $request = null, $content_type = '', $headers = array())
    {
        $data = array(
			'content' => null,
			'error'	  => null
		);

        $curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
        curl_setopt($curl, CURLOPT_POST, 0);

        if($request) {
            if(empty($content_type)) $request = http_build_query($request, '', '&');
            if($content_type=='json') $request = json_encode($request);
        }

        if($type=='POST') 
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        }

        if(count($headers)>0)
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($curl); 
        $err = curl_error($curl);
		curl_close($curl); 

		$data['content'] = json_decode($response);
		$data['error'] = $err;

        return $data;
    }

    public function view($view = '', $data = array(), $overrideSession = true)
    {
        if (isset($data['title'])) {
            $data['title'] = $data['title'];
        } else {
            $data['title'] = 'Carta';
        }

        if(env('APP_ENV') == 'production'){
            $data['asset'] = 'secure_asset';
        } else {
            $data['asset'] = 'asset';
        }

        if ($overrideSession) {
            session(['last_url' => URL::current()] );
        }

        return View::make($view, $data);
    }

    // convert date from 31-12-2020 to 2020-12-31
    public function convertDate($date)
    {
        $new_date = explode('-', $date);
        return $new_date[2].'-'.$new_date[1].'-'.$new_date[0];
    }
    
    public function getTeritories()
    {
        return $this->teritory->where('st', 1)->orderby('name')->get();
    }

    public function getTeritoryByName($name)
    {
        return $this->teritory->where('name', $name)->first();
    }

    public function getProvince($teritory_id)
    {
        return $this->province
                    ->with('province_teritory')
                    ->whereHas('province_teritory', function($q) use($teritory_id) {
                            $q->where('teritory_id', $teritory_id);
                      }) 
                    ->orderby('name')
                    ->get();
    }

    public function getProvinceByName($name)
    {
        return $this->province->where('name', $name)->first();
    }

    public function getCity($province_id)
    {
        return $this->city->where('province_id', $province_id)->orderby('name')->get();
    }

    public function getCityByName($name)
    {
        return $this->city->where('name', $name)->first();
    }

    public function getSubDistrict($city_id)
    {
        return $this->sub_district->where('city_id', $city_id)->orderby('name')->get();
    }

    public function getSubDistrictByName($name)
    {
        return $this->sub_district->where('name', $name)->first();
    }

    public function getVillage($sub_district_id)
    {
        return $this->village->where('subdistrict_id', $sub_district_id)->orderby('name')->get();
    }

    public function getVillageByName($name)
    {
        return $this->village->where('name', $name)->first();
    }

    public function getCustomerCategory()
    {
        return $this->category->get();
    }

    public function getCustomerCategoryByName($name)
    {
        return $this->category->where('category', $name)->first();
    }

    public function getContactTitle()
    {
        return $this->contact_title->get();
    }

    public function getContactTitleByName($name)
    {
        return $this->contact_title->where('name', $name)->first();
    }

    public function getAverageHPL()
    {
        return $this->avg_hpl->get();
    }

    public function getAverageHPLByName($name)
    {
        return $this->avg_hpl->whereRaw('lower(name) = ? ', [trim(strtolower($name))])->first();
    }

    public function getOccupation()
    {
        return $this->occupation->get();
    }

    public function getOccupationByName($name)
    {
        return $this->occupation->whereRaw('lower(name) = ? ', [trim(strtolower($name))])->first();
    }

    public function getPosition()
    {
        return $this->position->get();
    }

    public function getPositionByName($name)
    {
        return $this->position->where('name', $name)->first();
    }
    
    public function getDataSource()
    {
        return $this->data_source->get();
    }

    public function getDataSourceByName($name)
    {
        return $this->data_source->where('name', $name)->first();
    }

    public function getProduct($keyword='')
    {
        $products = $this->product->select('product_id', 'product_code', 'product_name');
        if($keyword)
        {
            $products = $products->whereRaw('lower(product_name) like ? or lower(product_code) like ?', ['%'.trim(strtolower($keyword)).'%', '%'.trim(strtolower($keyword)).'%']);
        }

        $products = $products->orderby('product_name', 'ASC')->get();

        foreach ($products as $product) {
            $product->caption = $product->product_code . ' | ' . $product->product_name;
        }
        
        return $products;
    }

    public function getProductByType($type, $keyword='')
    {
        $products = $this->product->select('product_id', 'product_code', 'product_name');
                        
        if($type=='HPL')
        {
            $products = $products->whereRaw("product_code like 'S0CT%' and status='Y'");
        } elseif($type='Edging')
        {
            $products = $products->whereRaw("product_code like 'SECT%' and status='Y'");
        }

        if($keyword)
        {
            $products = $products->whereRaw('lower(product_name) like ? ', ['%'.trim(strtolower($keyword)).'%']);
        }

        $products = $products->orderby('product_id', 'ASC')->get();

        foreach ($products as $product) {
            $product->caption = $product->product_code . ' | ' . $product->product_name;
        }
        
        return $products;
    }

    public function getLeadStatus()
    {
        return $this->leadStatus->get();
    }

    public function getApprovalStatus()
    {
        return $this->approvalStatus->get();
    }

    public function getDetailActiveMenu($menu_carta_id)
    {
        $menu = array();
        $active_menu = json_decode(Session::get('active_menu'), true);
        if($active_menu)
        {
            foreach ($active_menu as $value) {
                if($value['menu_carta_id']==$menu_carta_id)
                {
                    $menu = array(
                        'view'      => $value['is_view'],
                        'create'    => $value['is_create'],
                        'edit'      => $value['is_edit'],
                        'delete'    => $value['is_delete'],
                        'detail'    => $value['is_detail'],
                        'approval'  => $value['is_approval']
                    );
                }
            }
        }
        
        return $menu;
    }

    public function getActiveLink()
    {
        $active_menu = json_decode(Session::get('active_menu'), true);
        $link = "/";
        if($active_menu)
        {
            foreach ($active_menu as $value) {
                if($value['is_view']) {
                    $link = $value['menu_carta']['link'];
                    break;
                }
            }
        }
        return $link;
    }

    public function getFollowUpHistoryMenu()
    {
        return $this->getDetailActiveMenu(Config::get('menu.menu.followup_history'));
    }

    public function getSalesHistoryMenu()
    {
        return $this->getDetailActiveMenu(Config::get('menu.menu.sales_history'));
    }

    public function getRedeemHistoryMenu()
    {
        return $this->getDetailActiveMenu(Config::get('menu.menu.redeem_history'));
    }

    public function createLog($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $log = LogActivity::create($input);
            $id = $log->id;
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $id;
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function getBrandByName($name)
    {
        return $this->brand->where('name', $name)->first();
    }

    public function getBrands()
    {
        return $this->brand->get();
    }

    public function getStaff($nik = null)
    {
        if(!$nik)
        {
            $url = "https://apps.vivere.co.id/snabsys/master/get_data_karyawan";
        } else {
            $url = "https://apps.vivere.co.id/snabsys/master/get_data_karyawan_nik/". $nik;
        }
        $result = $this->curlAPI('GET', $url);
        return $result['content'];
    }

    public function getActiveRoles()
    {
        return $this->roles->where('st', 1)->get();
    }
}
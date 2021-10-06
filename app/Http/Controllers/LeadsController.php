<?php

namespace App\Http\Controllers;

use App\Services\FollowUp\SFollowUpLeads;
use App\Services\Google\SGoogle;
use App\Services\Leads\SLeads;
use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_CopySheetToAnotherSpreadsheetRequest;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_GetSpreadsheetByDataFilterRequest;
use Illuminate\Support\Facades\Log;

class LeadsController extends Controller
{
    private $sLeads;
    private $sGlobal;
    private $sUser;
    private $sFollowUpLeads;
    private $sGoogle;

    public function __construct(
        SLeads $sLeads, 
        SGlobal $sGlobal, 
        SUser $sUser, 
        SFollowUpLeads $sFollowUpLeads,
        SGoogle $sGoogle
    )
    {
        $this->sLeads = $sLeads;
        $this->sGlobal = $sGlobal;
        $this->sUser = $sUser;
        $this->sFollowUpLeads = $sFollowUpLeads;
        $this->sGoogle = $sGoogle;
    }

    public function index()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.leads'));
        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();

        $assigned_to = $this->sUser->get();
        $lead_status = $this->sGlobal->getLeadStatus();

        $data = array(
            'title'         => 'Carta - Leads',
            'active_menu'   => 'Leads',
            'my_menu'       => collect($my_menu),
            'followup_menu' => collect($followup_menu),
            'assigned_to'   => $assigned_to,
            'lead_status'   => $lead_status,
        );

        return $this->sGlobal->view('leads.index', $data);
    }

    public function getList(Request $request)
    {
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;;
        $assigned_to = $request->assigned_to;
        $status = $request->status;
        $contact_name = $request->contact_name;

        $leads = $this->sLeads->get($start_date, $end_date, $assigned_to, $status, $contact_name, $request->start, $request->length, $request->order);
        $leads['draw'] = $request->draw;
        
        return $leads;
    }

    public function create()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $categories = $this->sGlobal->getCustomerCategory();
        $contact_title = $this->sGlobal->getContactTitle();
        $occupations = $this->sGlobal->getOccupation();
        $positions = $this->sGlobal->getPosition();
        $teritories = $this->sGlobal->getTeritories();
        $avg_hpls = $this->sGlobal->getAverageHPL();
        $data_sources = $this->sGlobal->getDataSource();
        $assigned_to = $this->sUser->get();
        
        $data = array(
            'title'         => 'Carta - Create Lead',
            'active_menu'   => 'Create Lead',
            'categories'    => $categories,
            'contact_titles'=> $contact_title,
            'occupations'   => $occupations,
            'positions'     => $positions,
            'teritories'    => $teritories,
            'avg_hpls'      => $avg_hpls,
            'data_sources'  => $data_sources,
            'assigned_to'   => $assigned_to,
        );

        return $this->sGlobal->view('leads.create', $data);
    }

    public function doCreate(Request $request)
    {
        $route = Route::current()->getName();

        $data = array(
            'code'    => 400,
            'message' => ''
        );

        $customer_name     = $request->customer_name;
        $customer_category = $request->customer_category;
        $contact_title_id  = $request->contact_title;
        $contact_name      = $request->contact_name;
        $contact_phone     = $request->contact_phone;
        $contact_email     = $request->contact_email;
        $occupation        = $request->occupation;
        $occupation_name   = $request->occupation_name;
        $other_occupation  = $request->other_occupation;
        $position_id       = $request->position;
        $customer_dob      = $request->customer_dob;
        $address           = $request->address;
        $teritory_id       = $request->teritory;
        $province_id       = $request->province;
        $city_id           = $request->city;
        $subdistrict_id    = $request->subdistrict;
        $village_id        = $request->village;
        $postal_code       = $request->postal_code;
        $ecatalog          = $request->ecatalog;
        $avg_hpl           = $request->avg_hpl;
        $data_source_id    = $request->data_source;
        $assigned_to       = $request->assigned_to;
        $notes             = $request->notes;
        $products          = $request->products;
        
        if(!in_array($contact_phone[0], ['0', '2', '8']))
        {
            if($route=='leads.create.post')
            {
                return redirect()->back()->with('contact_phone.error', 'Invalid contact phone format')->withInput();
            } elseif($route=='api.leads.create')
            {
                $data['message'] = 'Invalid contact phone format';
                return response()->json($data, $data['code']);
            }
        }
        if($contact_phone[0]=='0')
        {
            if(!in_array($contact_phone[1], ['2', '8']))
            {
                if($route=='leads.create.post')
                {
                    return redirect()->back()->with('contact_phone.error', 'Invalid contact phone format')->withInput();
                } elseif($route=='api.leads.create')
                {
                    $data['message'] = 'Invalid contact phone format';
                    return response()->json($data, $data['code']);
                }   
            }
        }

        $contact_phone = str_replace('-', '', $contact_phone);
        if($contact_phone[0]=='0')
        {
            $contact_phone = substr($contact_phone, 1, strlen($contact_phone));
        }

        $active_nik = null;
        $is_e_catalog = 0;
        if($route=='leads.create.post')
        {
            $active_nik = $request->session()->get('nik');
            $occupation_id = $occupation;
            $avg_hpl_id = $avg_hpl;
            $is_e_catalog = $ecatalog;
        } elseif($route=='api.leads.create')
        {
            $avg_hpl_id = null;
            if($avg_hpl)
            {
                $m_avg_hpl = $this->sGlobal->getAverageHPLByName($avg_hpl)->first();
                $avg_hpl_id = $m_avg_hpl->id;
            }

            $occupation_id = null;
            if($occupation)
            {
                $m_occupation = $this->sGlobal->getOccupationByName($occupation);
                $occupation_id = $m_occupation->id;
                $occupation_name = $occupation;
            }

            if($ecatalog)
            {
                if($ecatalog=='Yes') $is_e_catalog=1;
            }
        }

        $duplicate = null;
        if($contact_phone)
            $duplicate = $this->sLeads->findDuplicateContactPhone($contact_phone);

        $input = array(
            'customer_name'        => $customer_name,
            'customer_category_id' => ($customer_category) ? $customer_category : null,
            'contact_title_id'     => ($contact_title_id) ? $contact_title_id : null,
            'contact_name'         => ($contact_name) ? $contact_name : null,
            'contact_phone'        => ($contact_phone) ? $contact_phone : null,
            'contact_email'        => ($contact_email) ? $contact_email : null,
            'occupation_id'        => ($occupation_id) ? $occupation_id : null,
            'other_occupation'     => ($occupation_name) && ($occupation_name=='Others') ? $other_occupation : null,
            'position_id'          => ($position_id) ? $position_id : null,
            'customer_dob'         => ($customer_dob) ? $customer_dob : null,
            'detail_address'       => $address,
            'teritory_id'          => ($teritory_id) ? $teritory_id : null,
            'province_id'          => ($province_id) ? $province_id : null,
            'city_id'              => ($city_id) ? $city_id : null,
            'sub_district_id'      => ($subdistrict_id) ? $subdistrict_id : null,
            'village_id'           => ($village_id) ? $village_id : null,
            'postal_code'          => ($postal_code) ? $postal_code : null,
            'is_e_catalog'         => ($is_e_catalog) ? $is_e_catalog : 0,
            'average_hpl_id'       => ($avg_hpl_id) ? $avg_hpl_id : null,
            'data_source_id'       => ($data_source_id) ? $data_source_id : null,
            'assigned_to'          => ($assigned_to) ? $assigned_to : null,
            'notes'                => ($notes) ? $notes : null,
            'products'             => ($products) ? $products : null,
            'status_id'            => 1,
            'created_at'           => date('Y-m-d H:i:s'),
            'created_by'           => $active_nik,
            'duplicate'            => $duplicate,
        );
        
        $created = $this->sLeads->create($input);
        if(!$created['status'])
        {
            if($route=='leads.create.post')
            {
                alert()->error('Error', $created['message']);
                return redirect()->back()->withInput($request->all());
            } elseif($route=='api.leads.create') {
                $data['message'] = $created['message'];
                return response()->json($data, $data['code']);
            }
        }

        $msg_duplicate = "";
        if($duplicate==1) $msg_duplicate="with duplicate contact phone.";

        if($route=='leads.create.post')
        {
            alert()->success('Success', 'Data created successfuly '. $msg_duplicate);
            return redirect()->route('leads');
        } elseif($route=='api.leads.create')
        {
            $data['code'] = 200;
            $data['message'] = 'Data created successfuly ' . $msg_duplicate;
            return response()->json($data, $data['code']);
        }
    }

    public function detail($id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }
        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.leads'));
        $followup_menu = $this->sGlobal->getFollowUpHistoryMenu();
        $lead = $this->sLeads->findById($id);

        $data = array(
            'title'         => 'Carta - Detail Lead',
            'active_menu'   => 'Detail Lead',
            'lead'          => $lead,
            'my_menu'       => collect($my_menu),
            'followup_menu' => collect($followup_menu),
        );

        return $this->sGlobal->view('leads.detail', $data);
        
    }

    public function edit($id)
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $lead = $this->sLeads->findById($id);
        $categories = $this->sGlobal->getCustomerCategory();
        $contact_title = $this->sGlobal->getContactTitle();
        $occupations = $this->sGlobal->getOccupation();
        $positions = $this->sGlobal->getPosition();
        $teritories = $this->sGlobal->getTeritories();
        $province = $this->sGlobal->getProvince($lead->teritory_id);
        $city = $this->sGlobal->getCity($lead->province_id);
        $subdistrict = $this->sGlobal->getSubDistrict($lead->city_id);
        $village = $this->sGlobal->getVillage($lead->sub_district_id);
        $avg_hpls = $this->sGlobal->getAverageHPL();
        $data_sources = $this->sGlobal->getDataSource();
        $assigned_to = $this->sUser->get();
        $lead_status = $this->sGlobal->getLeadStatus();

        $data = array(
            'title'         => 'Carta - Edit Lead',
            'active_menu'   => 'Edit Lead',
            'lead'          => $lead,
            'categories'    => $categories,
            'contact_titles'=> $contact_title,
            'occupations'   => $occupations,
            'positions'     => $positions,
            'teritories'    => $teritories,
            'provinces'     => $province,
            'cities'        => $city,
            'subdistricts'  => $subdistrict,
            'villages'      => $village,
            'avg_hpls'      => $avg_hpls,
            'data_sources'  => $data_sources,
            'assigned_to'   => $assigned_to,
            'lead_status'   => $lead_status,
        );

        return $this->sGlobal->view('leads.edit', $data);
    }

    public function doUpdate(Request $request)
    {
        $lead_id           = $request->lead_id;
        $customer_name     = $request->customer_name;
        $customer_category = $request->customer_category;
        $contact_title_id  = $request->contact_title;
        $contact_name      = $request->contact_name;
        $contact_phone     = $request->contact_phone;
        $contact_email     = $request->contact_email;
        $occupation        = $request->occupation;
        $occupation_name   = $request->occupation_name;
        $other_occupation  = $request->other_occupation;
        $position_id       = $request->position;
        $customer_dob      = $request->customer_dob;
        $address           = $request->address;
        $teritory_id       = $request->teritory;
        $province_id       = $request->province;
        $city_id           = $request->city;
        $subdistrict_id    = $request->subdistrict;
        $village_id        = $request->village;
        $postal_code       = $request->postal_code;
        $ecatalog          = $request->ecatalog;
        $avg_hpl           = $request->avg_hpl;
        $data_source_id    = $request->data_source;
        $assigned_to       = $request->assigned_to;
        $notes             = $request->notes;
        $products          = $request->products;
        $lead_status       = $request->lead_status;
        $active_nik        = $request->session()->get('nik');

        if(!in_array($contact_phone[0], ['0', '2', '8']))
        {
            return redirect()->back()->with('contact_phone.error', 'Invalid contact phone format')->withInput();
        }

        if($contact_phone[0]=='0')
        {
            if(!in_array($contact_phone[1], ['2', '8']))
            {
                return redirect()->back()->with('contact_phone.error', 'Invalid contact phone format')->withInput();
            }
        }

        $contact_phone = str_replace('-', '', $contact_phone);
        if($contact_phone[0]=='0')
        {
            $contact_phone = substr($contact_phone, 1, strlen($contact_phone));
        }
        
        $input = array(
            'customer_name'        => $customer_name,
            'customer_category_id' => ($customer_category) ? $customer_category : null,
            'contact_title_id'     => ($contact_title_id) ? $contact_title_id : null,
            'contact_name'         => ($contact_name) ? $contact_name : null,
            'contact_phone'        => ($contact_phone) ? $contact_phone : null,
            'contact_email'        => ($contact_email) ? $contact_email : null,
            'occupation_id'        => ($occupation) ? $occupation : null,
            'other_occupation'     => ($occupation_name) && ($occupation_name=='Others') ? $other_occupation : null,
            'position_id'          => ($position_id) ? $position_id : null,
            'customer_dob'         => ($customer_dob) ? $customer_dob : null,
            'detail_address'       => $address,
            'teritory_id'          => ($teritory_id) ? $teritory_id : null,
            'province_id'          => ($province_id) ? $province_id : null,
            'city_id'              => ($city_id) ? $city_id : null,
            'sub_district_id'      => ($subdistrict_id) ? $subdistrict_id : null,
            'village_id'           => ($village_id) ? $village_id : null,
            'postal_code'          => ($postal_code) ? $postal_code : null,
            'is_e_catalog'         => ($ecatalog) ? $ecatalog : 0,
            'average_hpl_id'       => ($avg_hpl) ? $avg_hpl : null,
            'data_source_id'       => ($data_source_id) ? $data_source_id : null,
            'assigned_to'          => ($assigned_to) ? $assigned_to : null,
            'notes'                => ($notes) ? $notes : null,
            'products'             => ($products) ? $products : null,
            'status_id'            => $lead_status,
            'updated_at'           => date('Y-m-d H:i:s'),
            'updated_by'           => ($active_nik) ? $active_nik : null
        );
        
        $created = $this->sLeads->update($lead_id, $input);
        if(!$created['status'])
        {
            alert()->error('Error', $created['message']);
            return redirect()->back()->withInput($request->all());
        }

        alert()->success('Success', 'Data updated successfuly.');
        return redirect()->route('leads.detail', ['id'=>$lead_id]);
    }

    public function doDelete($id)
    {
        $deleted = $this->sLeads->delete($id);
        if(!$deleted['status'])
        {
            alert()->error('Error', $deleted['message']);
            return redirect()->back();
        }
        alert()->success('Success', 'Data deleted successfuly.');
        return redirect()->route('leads');
    }

    public function doImport(Request $request)
    {
        $file = $request->file('import_file');
        if($request->hasFile('import_file')){
            $nama_file = rand().$file->getClientOriginalName();
            $file->move(base_path('public/import/'), $nama_file);

            $path = base_path('public/import/'.$nama_file);

            Excel::load($path, function($reader){
                $data = $reader->toArray();
                $data_input = $data[0];
                if(count($data_input)>0){
                    foreach ($data_input as $key => $value) {
                        if(empty($value['customer_name'])) break;
                        $created_id = $this->doImportRow(
                            $value['customer_name'], 
                            $value['customer_category'], 
                            $value['contact_title'], 
                            $value['contact_name'], 
                            $value['contact_phone'],
                            $value['contact_email'], 
                            $value['occupation'], 
                            $value['other_occupation'], 
                            $value['position'], 
                            $value['customer_birthday'],
                            $value['address'], 
                            $value['teritory'], 
                            $value['provinsi'], 
                            $value['kota'], 
                            $value['kecamatan'],
                            $value['kelurahan'], 
                            $value['kode_pos'], 
                            $value['e_catalog'], 
                            $value['average_hpl'], 
                            $value['data_source'],
                            $value['assigned_to'], 
                            $value['notes'], 
                            $value['products']
                        );
                    }
                }
            });
            
            alert()->success('Success', 'Data import successfuly.');
        }

        return redirect()->route('leads');
    }

    public function doImportGoogleSheet()
    {
        $client = new Google_Client();
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
        $client->setAuthConfig(public_path('template/vivere-sheet-credential.json'));
        $service = new Google_Service_Sheets($client);
        if(env('APP_ENV')!='production')
            $spreadsheetId = '11imJqqqRyQJ-nZjTnIv1j6L_5x__jd4n7uOtcxX3VOA';
        else $spreadsheetId = '1xZbnrQYaZa8juqXC61ltYoNB1_dpIVIoWWHHGFO0GVQ';

        $range = '1!A2:X1000';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $rows = $response->getValues();
        if(empty($rows))
        {
            alert()->error('Error', 'No data found');
            return redirect()->route('leads');
        }

        foreach ($rows as $value) {
            // kalo nama kosong langsung keluar;
            if(empty($value[0])) break;
            $created_id = $this->doImportRow(
                $value[0], $value[1], $value[2], $value[3], $value[4],
                $value[5], $value[6], $value[7], $value[8], $value[9],
                $value[10], $value[11], $value[12], $value[13], $value[14],
                $value[15], $value[16], $value[17], 
                (isset($value[18])) ? $value[18] : '', 
                (isset($value[19])) ? $value[19] : '',
                (isset($value[20])) ? $value[20] : '', 
                (isset($value[21])) ? $value[21] : '', 
                (isset($value[22])) ? $value[22] : ''
            );
        }
        
        // make copy of sheet 1
        $copySheet = $this->sGoogle->copyTo($service, $spreadsheetId, $spreadsheetId);

        // renamed new sheet
        $renamed = $this->sGoogle->renameSheet($service, $spreadsheetId);

        // clear first sheet
        $cleared = $this->sGoogle->clearValues($service, $spreadsheetId, $range);

        alert()->success('Success', 'Data imported successfully');
        return redirect()->route('leads');
    }

    public function doImportRow(
        $customer_name_val, $customer_category_val, $contact_title_val, $contact_name_val, $contact_phone_val, 
        $contact_email_val, $occupation_val, $other_occupation_val, $position_val, $customer_birthday_val, 
        $address_val, $teritory_val, $province_val, $city_val, $sub_district_val, 
        $village_val, $postal_code_val, $e_catalog_val, $average_hpl_val, $datasource_val, 
        $assigned_to_val, $notes_val, $products_val
    )
    {
        $customer_category_id = null;
        if($customer_category_val && $customer_category_val!='-')
        {
            $customer_category = $this->sGlobal->getCustomerCategoryByName($customer_category_val);
            if($customer_category) $customer_category_id = $customer_category->customer_category_id;
        }

        $contact_title_id = null;
        if($contact_title_val && $contact_title_val!='-')
        {
            $contact_title = $this->sGlobal->getContactTitleByName($contact_title_val);
            if($contact_title) $contact_title_id = $contact_title->contact_title_id;
        }

        $occupation_id = null;
        if($occupation_val && $occupation_val!='-')
        {
            $occupation = $this->sGlobal->getOccupationByName($occupation_val);
            if($occupation) $occupation_id = $occupation->id;
        }

        $other_occupation = null;
        if($other_occupation_val && $other_occupation_val!='-')
        {
            if($occupation_val=='Others')
            {
                $other_occupation = $other_occupation_val;
            }
        }

        $position_id = null;
        if($position_val && $position_val!='-')
        {
            $position = $this->sGlobal->getPositionByName($position_val);
            if($position) $position_id = $position->position_id;
        }

        $dob = null;
        if($customer_birthday_val && $customer_birthday_val!='-')
        {
            $dob = date('Y-m-d', strtotime($customer_birthday_val));
        }

        $teritory_id = null;
        if($teritory_val && $teritory_val!='-')
        {
            $teritory = $this->sGlobal->getTeritoryByName($teritory_val);
            if($teritory) $teritory_id = $teritory->teritory_id;
        }

        $province_id = null;
        if($province_val && $province_val!='-')
        {
            $province = $this->sGlobal->getProvinceByName($province_val);
            if($province) $province_id = $province->province_id;
        }

        $city_id = null;
        if($city_val && $city_val!='-')
        {
            $city = $this->sGlobal->getCityByName($city_val);
            if($city) $city_id = $city->city_id;
        }

        $subdistrict_id = null;
        if($sub_district_val && $sub_district_val!='-')
        {
            $subdistrict = $this->sGlobal->getSubDistrictByName($sub_district_val);
            if($subdistrict) $subdistrict_id = $subdistrict->subdistrict_id;
        }

        $village_id = null;
        if($village_val && $village_val!='-')
        {
            $village = $this->sGlobal->getVillageByName($village_val);
            if($village) $village_id = $village->village_id;
        }

        $is_e_catalog = 0;
        if($e_catalog_val && $e_catalog_val!='-')
        {
            if(strtoupper($e_catalog_val)=='YES')
                $is_e_catalog = 1;
        }

        $avg_hpl_id = null;
        if($average_hpl_val && $average_hpl_val!='-')
        {
            $avg_hpl = $this->sGlobal->getAverageHPLByName($average_hpl_val);
            if($avg_hpl) $avg_hpl_id = $avg_hpl->id;
        }

        $data_source_id = null;
        if($datasource_val && $datasource_val!='-')
        {
            $data_source = $this->sGlobal->getDataSourceByName($datasource_val);
            if($data_source) $data_source_id = $data_source->data_source_id;
        }

        $duplicate = null;
        if($contact_phone_val)
            $duplicate = $this->sLeads->findDuplicateContactPhone($contact_phone_val);

        $input = array(
            'customer_name'         => $customer_name_val,
            'customer_category_id'  => $customer_category_id, 
            'contact_title_id'      => $contact_title_id, 
            'contact_name'          => $contact_name_val, 
            'contact_phone'         => $contact_phone_val, 
            'contact_email'         => ($contact_email_val && $contact_email_val!='-') ? $contact_email_val : null, 
            'occupation_id'         => $occupation_id, 
            'other_occupation'      => $other_occupation,
            'position_id'           => $position_id, 
            'customer_dob'          => $dob,
            'detail_address'        => trim($address_val),
            'teritory_id'           => $teritory_id,
            'province_id'           => $province_id,
            'city_id'               => $city_id,
            'sub_district_id'       => $subdistrict_id,
            'village_id'            => $village_id,
            'postal_code'           => ($postal_code_val) ? $postal_code_val : null,
            'is_e_catalog'          => $is_e_catalog,
            'average_hpl_id'        => $avg_hpl_id,
            'data_source_id'        => $data_source_id,
            'assigned_to'           => ($assigned_to_val && $assigned_to_val!='-') ? $assigned_to_val : null,
            'notes'                 => ($notes_val) ? trim($notes_val) : null,
            'products'              => ($products_val) ? trim($products_val) : null,
            'status_id'             => 1,
            'created_at'            => date('Y-m-d H:i:s'),
            'duplicate'             => $duplicate
        );
        $created = $this->sLeads->create($input);
        return $created['id'];
    }

    public function doDownload($filename)
    {
        $pathToFile = public_path('/template/'.$filename);
        return response()->download($pathToFile);
    }

    public function viewAccount(Request $request)
    {
        $account = $this->sLeads->findAccountByLeadId($request->id);
        return response()->json($account, 200);
    }

    public function downloadTemplate()
    {
        $filename = 'template-import-data-leads.xls';
        $path= public_path('/template/template-import-data-leads.xls'). "";

        $headers = array(
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        );

        return response()->download($path, $filename, $headers);
    }
}

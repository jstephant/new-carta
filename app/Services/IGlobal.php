<?php

namespace App\Services;

interface IGlobal 
{
    public function curlAPI($type, $url, $request=null, $content_type='', $headers=array());
    public function view($view = '', $data = array(), $overrideSession = true);
    public function convertDate($date);

    public function getTeritories();
    public function getTeritoryByName($name);
    
    public function getProvince($teritory_id);
    public function getProvinceByName($name);

    public function getCity($province_id);
    public function getCityByName($name);

    public function getSubDistrict($city_id);
    public function getSubDistrictByName($name);

    public function getVillage($sub_district_id);
    public function getVillageByName($name);

    public function getCustomerCategory();
    public function getCustomerCategoryByName($name);

    public function getContactTitle();
    public function getContactTitleByName($name);

    public function getAverageHPL();
    public function getAverageHPLByName($name);

    public function getOccupation();
    public function getOccupationByName($name);
    
    public function getPosition();
    public function getPositionByName($name);

    public function getDataSource();
    public function getDataSourceByName($name);
    public function getProduct($keyword='');
    public function getProductByType($type, $keyword='');
    
    // lead status
    public function getLeadStatus();

    // approval status
    public function getApprovalStatus();

    // menu
    public function getDetailActiveMenu($menu_carta_id);
    public function getActiveLink();

    public function getFollowUpHistoryMenu();
    public function getSalesHistoryMenu();
    public function getRedeemHistoryMenu();

    // log
    public function createLog($input);

    //brand
    public function getBrandByName($name);
    public function getBrands();

    // karyawan
    public function getStaff($nik=null);

    // role
    public function getActiveRoles();
}
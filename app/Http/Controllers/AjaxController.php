<?php

namespace App\Http\Controllers;

use App\Services\SGlobal;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    private $sGlobal;

    public function __construct(SGlobal $sGlobal)
    {
        $this->sGlobal = $sGlobal;    
    }

    public function getProvince($teritory_id)
    {
        $provinces = $this->sGlobal->getProvince($teritory_id);
        return response()->json($provinces, 200);
    }

    public function getCity($province_id)
    {
        $cities = $this->sGlobal->getCity($province_id);
        return response()->json($cities, 200);
    }

    public function getSubDistrict($city_id)
    {
        $subdistricts = $this->sGlobal->getSubDistrict($city_id);
        return response()->json($subdistricts, 200);
    }

    public function getVillage($subdistrict_id)
    {
        $villages = $this->sGlobal->getVillage($subdistrict_id);
        return response()->json($villages, 200);
    }

    public function getProduct(Request $request)
    {
        $products = $this->sGlobal->getProduct($request->q);
        return response()->json($products, 200);
    }

    public function getProductByType(Request $request)
    {
        $products = $this->sGlobal->getProductByType($request->type, $request->q);
        return response()->json($products, 200);
    }
}

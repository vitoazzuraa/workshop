<?php

namespace App\Http\Controllers;

use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $provinces = RegProvince::orderBy('name')->get();
        return view('pages.wilayah.index', compact('provinces'));
    }

    public function getRegency(Request $req)
    {
        $data = RegRegency::where('province_id', $req->province_id)
                    ->orderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    public function getDistrict(Request $req)
    {
        $data = RegDistrict::where('regency_id', $req->regency_id)
                    ->orderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    public function getVillage(Request $req)
    {
        $data = RegVillage::where('district_id', $req->district_id)
                    ->orderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }
}

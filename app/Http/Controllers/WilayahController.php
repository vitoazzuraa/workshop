<?php

namespace App\Http\Controllers;

use App\Models\WilayahKecamatan;
use App\Models\WilayahKelurahan;
use App\Models\WilayahKota;
use App\Models\WilayahProvinsi;

class WilayahController extends Controller
{
    public function index()
    {
        $provinsi = WilayahProvinsi::orderBy('nama')->get();
        return view('wilayah.index', compact('provinsi'));
    }

    public function kota($provinsiId)
    {
        $data = WilayahKota::where('id_provinsi', $provinsiId)->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'code' => 200, 'data' => $data]);
    }

    public function kecamatan($kotaId)
    {
        $data = WilayahKecamatan::where('id_kota', $kotaId)->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'code' => 200, 'data' => $data]);
    }

    public function kelurahan($kecamatanId)
    {
        $data = WilayahKelurahan::where('id_kecamatan', $kecamatanId)->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'code' => 200, 'data' => $data]);
    }
}

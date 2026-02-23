<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    // 1. Generate Sertifikat (Landscape A4)
    public function generateSertifikat()
    {
        $data = [
            'nama' => 'Christianus Primavito',
            'nomor' => '1234/B/UA.VOKASI/S.KM/PM.03/2026',
            'peran' => 'Peserta'
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
                  ->setPaper('a4', 'landscape'); // Set Landscape A4

        return $pdf->download('sertifikat.pdf');
    }

    // 2. Generate Undangan (Portrait A4)
    public function generateUndangan()
    {
        $data = [
            'nomor' => '123/B/DST/UA.VOKASI/2026',
            'tanggal' => '28 Februari 2026',
            'agenda' => 'Undangan Pertemuan'
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)
                  ->setPaper('a4', 'portrait'); // Set Portrait A4

        return $pdf->download('undangan.pdf');
    }
}

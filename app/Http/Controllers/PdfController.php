<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
    {
        $pdf = Pdf::loadView('pdf.sertifikat')->setPaper('a4','landscape');
        return $pdf->stream('sertifikat.pdf');
    }

    public function undangan()
    {
        $pdf = Pdf::loadView('pdf.undangan')->setPaper('a4','portrait');
        return $pdf->stream('undangan.pdf');
    }
}

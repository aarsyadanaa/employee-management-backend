<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generatePdf()
    {
        // Data yang akan dimasukkan ke dalam PDF
        $data = \App\Models\Pegawai::with(['golongan', 'jabatan', 'unit_kerja']);

        // Load view dan pass data ke PDF
        $pdf = Pdf::loadView('pdf_template', compact('data'));
        $pdf->headers->set('Access-Control-Allow-Origin', '*'); // Ganti '*' dengan domain spesifik jika diperlukan
        $pdf->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $pdf->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $pdf->headers->set('Access-Control-Allow-Credentials', 'true');

        return $pdf->download('data_pegawai.pdf');
    }
}

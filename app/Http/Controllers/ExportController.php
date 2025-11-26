<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportPDF()
    {
        $data = Transaction::all();
        $pdf = Pdf::loadView('exports.laporan_pdf', compact('data'));
        return $pdf->download('laporan_transaksi.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new \App\Exports\LaporanExport, 'laporan.xlsx');
    }
}

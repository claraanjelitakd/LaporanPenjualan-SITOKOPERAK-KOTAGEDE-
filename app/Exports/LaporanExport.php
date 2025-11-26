<?php

namespace App\Exports;


use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class LaporanExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    // Bawa filter tanggal dari controller (opsional)
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $query = Transaction::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
        }

        // Pilih kolom yang mau diexport
        return $query
            ->orderBy('tanggal_transaksi', 'desc')
            ->get([
                'id',
                'tanggal_transaksi',
                'total',
                'user_id',
            ]);
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal Transaksi',
            'Total',
            'User ID',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\LaporanProduksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanProduksiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return LaporanProduksi::with('user')
            ->get()
            ->map(function ($laporan) {
                return [
                    'Nama Karyawan' => $laporan->user->name,
                    'Tanggal' => $laporan->tanggal,
                    'Jenis Pekerjaan' => $laporan->jenis_pekerjaan,
                    'Jumlah' => $laporan->jumlah,
                    'Status' => $laporan->status,
                    'Keterangan' => $laporan->keterangan,
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama Karyawan', 'Tanggal', 'Jenis Pekerjaan', 'Jumlah', 'Status', 'Keterangan'];
    }
}

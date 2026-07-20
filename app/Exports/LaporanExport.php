<?php

namespace App\Exports;

use App\Models\Sekolah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    /**
     * Ambil semua data dari tabel sekolah utama untuk di-export
     */
    public function collection()
    {
        return Sekolah::all();
    }

    /**
     * Mengatur Judul Kolom di Excel (Baris Paling Atas)
     */
    public function headings(): array
    {
        return [
            'No',
            'NPSN',
            'Nama Sekolah',
            'Jenjang',
            'Status',
            'Akreditasi',
            'Provinsi',
            'Kabupaten/Kota',
            'Kecamatan',
            'Alamat',
            'No Telepon',
            'Email',
        ];
    }

    /**
     * Memetakan data dari database ke kolom Excel
     */
    public function map($sekolah): array
    {
        return [
            ++$this->rowNumber,
            $sekolah->npsn,
            $sekolah->nama_sekolah,
            strtoupper($sekolah->jenjang), // <-- Cukup panggil fungsinya langsung tanpa string key 'jenjang' =>
            $sekolah->status,
            $sekolah->akreditasi,
            $sekolah->provinsi,
            $sekolah->kabupaten_kota,
            $sekolah->kecamatan,
            $sekolah->alamat,
            $sekolah->no_telepon,
            $sekolah->email,
        ];
    }
}

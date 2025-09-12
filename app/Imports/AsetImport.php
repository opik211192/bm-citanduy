<?php

namespace App\Imports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AsetImport implements ToModel, WithHeadingRow
{
    protected $jenisAset;

    public function __construct($jenisAset)
    {
        $this->jenisAset = $jenisAset;
    }

    // Excel pakai header di baris pertama
    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // cek minimal harus ada kode_integrasi dan nama
        if (empty($row['kode_integrasi']) || empty($row['nama'])) {
            return null; // skip baris kosong
        }

        // Cari aset lama berdasarkan kode_integrasi
        $aset = Aset::where('kode_integrasi', $row['kode_integrasi'])->first();

        if ($aset) {
            // update hanya kolom yang ada di excel, kolom lain tetap
            $aset->updateOrCreate([
                'kode_bmn'       => $row['kode_bmn'] ?? $aset->kode_bmn,
                'nama_aset'      => $row['nama'] ?? $aset->nama_aset,
                'jenis_aset'     => $this->jenisAset,
                'wilayah_sungai' => $row['wilayah_sungai'] ?? $aset->wilayah_sungai,
                'das'            => $row['daerah_aliran_sungai'] ?? $aset->das,

                'province' => $row['provinsi'] ?? $aset->province,
                'city'     => $row['kabupaten_kota'] ?? $aset->city,
                'district' => $row['kecamatan'] ?? $aset->district,
                'village'  => $row['kelurahan'] ?? $aset->village,

                'lat'  => $row['latitude'] ?? $aset->lat,
                'long' => $row['longitude'] ?? $aset->long,
                'utm_x' => $aset->utm_x, // default tidak berubah
                'utm_y' => $aset->utm_y,

                'tahun_mulai_bangunan'   => $row['tahun_mulai_pembangunan'] ?? $aset->tahun_mulai_bangunan,
                'tahun_selesai_bangunan' => $row['tahun_selesai_pembangunan'] ?? $aset->tahun_selesai_bangunan,
                'kondisi_bangunan'       => $row['kondisi_bangunan'] ?? $aset->kondisi_bangunan,
                'status_operasi'         => $row['status_operasi'] ?? $aset->status_operasi,
                'kondisi_infrastruktur'  => $row['kondisi_infrastruktur'] ?? $aset->kondisi_infrastruktur,
            ]);

            return null; // return null biar tidak bikin record baru
        }

        // kalau tidak ada, buat aset baru
        return new Aset([
            'kode_integrasi' => $row['kode_integrasi'] ?? uniqid('ASSET_'),
            'kode_bmn'       => $row['kode_bmn'] ?? '',
            'nama_aset'      => $row['nama'] ?? '',
            'jenis_aset'     => $this->jenisAset,
            'wilayah_sungai' => $row['wilayah_sungai'] ?? '',
            'das'            => $row['daerah_aliran_sungai'] ?? '',

            'province' => $row['provinsi'] ?? null,
            'city'     => $row['kabupaten_kota'] ?? null,
            'district' => $row['kecamatan'] ?? null,
            'village'  => $row['kelurahan'] ?? null,

            'lat'  => $row['latitude'] ?? '',
            'long' => $row['longitude'] ?? '',
            'utm_x' => null,
            'utm_y' => null,

            'tahun_mulai_bangunan'   => $row['tahun_mulai_pembangunan'] ?? '',
            'tahun_selesai_bangunan' => $row['tahun_selesai_pembangunan'] ?? '',
            'kondisi_bangunan'       => $row['kondisi_bangunan'] ?? '',
            'status_operasi'         => $row['status_operasi'] ?? '',
            'kondisi_infrastruktur'  => $row['kondisi_infrastruktur'] ?? '',
        ]);
    }
}

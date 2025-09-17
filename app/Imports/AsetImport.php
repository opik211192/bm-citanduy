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

    public function headingRow(): int
    {
        return 2;
    }

    // Helper ambil nilai dari beberapa kemungkinan nama kolom
    private function getExcelValue(array $row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (!empty($row[$key])) {
                return $row[$key];
            }
        }
        return $default;
    }

    public function model(array $row)
    {
        if (empty($row['kode_integrasi']) || empty($row['nama'])) {
            return null;
        }

        $aset = Aset::where('kode_integrasi', $row['kode_integrasi'])->first();

        // alias untuk tahun mulai
        $tahunMulai = $this->getExcelValue($row, ['tahun_mulai_pembangunan', 'tahun_pembangunan'], $aset->tahun_mulai_bangunan ?? '');
        $tahunSelesai = $this->getExcelValue($row, ['tahun_selesai_pembangunan'], $aset->tahun_selesai_bangunan ?? '');

        if ($aset) {
            $aset->update([
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

                // pakai helper alias
                'tahun_mulai_bangunan'   => $tahunMulai,
                'tahun_selesai_bangunan' => $tahunSelesai,
                'kondisi_bangunan'       => $row['kondisi_bangunan'] ?? $aset->kondisi_bangunan,
                'status_operasi'         => $row['status_operasi'] ?? $aset->status_operasi,
                'kondisi_infrastruktur'  => $row['kondisi_infrastruktur'] ?? $aset->kondisi_infrastruktur,
            ]);

            return null;
        }

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

            // pakai helper alias juga
            'tahun_mulai_bangunan'   => $this->getExcelValue($row, ['tahun_mulai_pembangunan', 'tahun_pembangunan'], ''),
            'tahun_selesai_bangunan' => $this->getExcelValue($row, ['tahun_selesai_pembangunan'], ''),
            'kondisi_bangunan'       => $row['kondisi_bangunan'] ?? '',
            'status_operasi'         => $row['status_operasi'] ?? '',
            'kondisi_infrastruktur'  => $row['kondisi_infrastruktur'] ?? '',
        ]);
    }
}

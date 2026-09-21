<?php

namespace App\Imports;

use App\Models\Bm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BmImport implements WithMultipleSheets
{
    /**
     * Sheet yang dipilih user untuk diimport
     */
    protected $selectedSheets;

    /**
     * Total data yang berhasil diimport
     */
    public $importedCount = 0;

    /**
     * Sheet yang berhasil diimport
     */
    public $importedSheets = [];

    public function __construct(array $selectedSheets = [])
    {
        $this->selectedSheets = $selectedSheets;
    }

    /**
     * Mapping setiap sheet ke BmSheetImport
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->selectedSheets as $sheetName) {
            $sheetImport = new BmSheetImport($sheetName);
            $sheets[$sheetName] = $sheetImport;
            $this->importedSheets[$sheetName] = $sheetImport;
        }

        return $sheets;
    }

    /**
     * Hitung total data yang diimport dari semua sheet
     */
    public function getTotalImported(): int
    {
        $total = 0;
        foreach ($this->importedSheets as $sheet) {
            $total += $sheet->getImportedCount();
        }
        return $total;
    }
}


class BmSheetImport implements ToModel, WithStartRow
{
    protected $namaPekerjaan;
    protected $importedCount = 0;
    protected $debugFirstRow = true;

    public function __construct(string $namaPekerjaan)
    {
        $this->namaPekerjaan = $namaPekerjaan;
    }

    /**
     * Data mulai dari baris ke-7
     * Row 1: kosong
     * Row 2-3: judul
     * Row 4: kosong
     * Row 5-6: header tabel
     * Row 7+: data
     */
    public function startRow(): int
    {
        return 7;
    }

    public function model(array $row)
    {
        // Debug: log baris pertama untuk verifikasi mapping kolom
        if ($this->debugFirstRow) {
            \Illuminate\Support\Facades\Log::info("BmImport [{$this->namaPekerjaan}] Row data:", $row);
            $this->debugFirstRow = false;
        }

        // Skip baris kosong (jika kolom C / index 2 kosong = tidak ada nama BM)
        $kodeBm = trim($row[2] ?? '');
        if (empty($kodeBm)) {
            return null;
        }

        // Ambil koordinat UTM
        $utmX = str_replace(',', '.', $row[3] ?? '');
        $utmY = str_replace(',', '.', $row[4] ?? '');

        // Skip jika koordinat kosong
        if (empty($utmX) || empty($utmY)) {
            return null;
        }

        // Convert UTM -> Latitude Longitude (Zone 49, Southern Hemisphere)
        $coord = $this->utmToLatLon($utmX, $utmY, 49, true);

        $tinggiOrthometrik = str_replace(',', '.', $row[5] ?? '');

        // Keterangan: ambil dari kolom G (index 6), handle multiline text
        $keterangan = trim(str_replace(["\r\n", "\r"], "\n", $row[6] ?? ''));

        \Illuminate\Support\Facades\Log::info("BmImport [{$this->namaPekerjaan}] Saving: kode_bm={$kodeBm}, keterangan={$keterangan}");

        // UpdateOrCreate: jika kode_bm + nama_pekerjaan sudah ada, update. Jika belum, buat baru.
        $bm = Bm::updateOrCreate(
            [
                'kode_bm' => $kodeBm,
                'nama_pekerjaan' => strtoupper($this->namaPekerjaan),
            ],
            [
                'provinsi' => '-',
                'desa' => '-',
                'kecamatan' => '-',
                'kota' => '-',
                'utm_x' => $utmX,
                'utm_y' => $utmY,
                'zone' => '49',
                'latitude' => $coord['latitude'],
                'longitude' => $coord['longitude'],
                'tinggi_orthometrik' => $tinggiOrthometrik,
                'keterangan' => $keterangan,
                'qr_code' => str_replace(' ', '', $kodeBm),
            ]
        );

        $this->importedCount++;

        return null; // Return null karena sudah pakai updateOrCreate
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Convert UTM ke Latitude Longitude
     * Zone default: 49
     */
    private function utmToLatLon($x, $y, $zone = 49, $south = true)
    {
        $x = (float) $x;
        $y = (float) $y;

        $a = 6378137.0;
        $eccSquared = 0.00669438;
        $k0 = 0.9996;

        $e1 = (1 - sqrt(1 - $eccSquared)) / (1 + sqrt(1 - $eccSquared));

        $x = $x - 500000.0;

        // SOUTHERN HEMISPHERE
        if ($south) {
            $y -= 10000000.0;
        }

        $LongOrigin = ($zone - 1) * 6 - 180 + 3;

        $eccPrimeSquared = $eccSquared / (1 - $eccSquared);

        $M = $y / $k0;

        $mu = $M / (
            $a * (
                1
                - $eccSquared / 4
                - 3 * $eccSquared * $eccSquared / 64
                - 5 * pow($eccSquared, 3) / 256
            )
        );

        $phi1Rad =
            $mu
            + (3 * $e1 / 2 - 27 * pow($e1, 3) / 32) * sin(2 * $mu)
            + (21 * $e1 * $e1 / 16 - 55 * pow($e1, 4) / 32) * sin(4 * $mu)
            + (151 * pow($e1, 3) / 96) * sin(6 * $mu);

        $N1 = $a / sqrt(1 - $eccSquared * pow(sin($phi1Rad), 2));
        $T1 = pow(tan($phi1Rad), 2);
        $C1 = $eccPrimeSquared * pow(cos($phi1Rad), 2);
        $R1 = $a * (1 - $eccSquared) /
            pow(1 - $eccSquared * pow(sin($phi1Rad), 2), 1.5);

        $D = $x / ($N1 * $k0);

        $lat = $phi1Rad - (
            $N1 * tan($phi1Rad) / $R1
        ) * (
            ($D * $D) / 2
            - (
                5
                + 3 * $T1
                + 10 * $C1
                - 4 * $C1 * $C1
                - 9 * $eccPrimeSquared
            ) * pow($D, 4) / 24
        );

        $lat = rad2deg($lat);

        $lon = (
            $D
            - (
                1
                + 2 * $T1
                + $C1
            ) * pow($D, 3) / 6
        ) / cos($phi1Rad);

        $lon = $LongOrigin + rad2deg($lon);

        return [
            'latitude' => round($lat, 8),
            'longitude' => round($lon, 8),
        ];
    }
}
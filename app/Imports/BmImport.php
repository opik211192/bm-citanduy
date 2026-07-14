<?php

namespace App\Imports;

use App\Models\Bm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BmImport implements ToModel, WithStartRow
{
    /**
     * Mulai baca dari baris ke-3
     * karena row 1-2 adalah header excel
     */
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        // Ambil koordinat UTM
        $utmX = str_replace(',', '.', $row[6] ?? '');
        $utmY = str_replace(',', '.', $row[7] ?? '');

        // Convert UTM -> Latitude Longitude
        $coord = $this->utmToLatLon($utmX, $utmY, 49, true);

        return new Bm([
            'kode_bm' => trim($row[1] ?? ''),
            'nama_pekerjaan' => strtoupper(trim($row[2] ?? '')),

            'provinsi' => '-',

            'desa' => trim($row[3] ?? ''),
            'kecamatan' => trim($row[4] ?? ''),
            'kota' => trim($row[5] ?? ''),

            'utm_x' => $utmX,
            'utm_y' => $utmY,

            'zone' => '49',

            'latitude' => $coord['latitude'],
            'longitude' => $coord['longitude'],

            'tinggi_orthometrik' => $row[8] ?? '',
        ]);
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
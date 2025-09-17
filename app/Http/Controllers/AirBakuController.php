<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirBakuResource;
use App\Models\AirBaku;
use App\Models\AirBakuPhoto;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class AirBakuController extends Controller
{
    public function index(Request $request)
    {
        $dependantDropdownController = new DependantDropdownController();
        $provinces = $dependantDropdownController->provinces();

        if ($request->ajax()) {
            $columns = [
                0 => 'id',
                1 => 'nama_aset',
                2 => 'jenis_aset',
                3 => 'kode_bmn',
                4 => 'lat',
                5 => 'tahun_pembangunan',
                6 => 'status_operasi',
                7 => 'status_pekerjaan',
                8 => 'updated_at',
            ];

            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDirection = $request->input('order.0.dir');

            $jenisFilter = $request->input('jenis_aset');

            $query = AirBaku::query();

            if (!empty($jenisFilter)) {
                $query->where('jenis_aset', $jenisFilter);
            }

            // search
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('nama_aset', 'LIKE', "%{$search}%")
                    ->orWhere('jenis_aset', 'LIKE', "%{$search}%")
                    ->orWhere('kode_bmn', 'LIKE', "%{$search}%")
                    ->orWhere('lat', 'LIKE', "%{$search}%")
                    ->orWhere('long', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_pembangunan', 'LIKE', "%{$search}%")
                    ->orWhere('status_operasi', 'LIKE', "%{$search}%")
                    ->orWhere('status_pekerjaan', 'LIKE', "%{$search}%")
                    ->orWhere('sungai', 'LIKE', "%{$search}%")
                    ->orWhere('updated_at', 'LIKE', "%{$search}%");
                });
            }

            $totalData = AirBaku::count();
            $totalFiltered = $query->count();

            $airBakus = $query->offset($start)
                ->limit($limit)
                ->orderBy($orderColumn, $orderDirection)
                ->get();

            $data = [];
            foreach ($airBakus as $index => $air) {

                $nestedData['DT_RowIndex'] = $start + $index + 1;
                $nestedData['nama_aset'] = $air->nama_aset;
                $nestedData['jenis_aset'] = $air->jenis_aset;
                $nestedData['kode_bmn'] = $air->kode_bmn;
                $nestedData['koordinat'] = $air->lat . ', ' . $air->long;
                $nestedData['tahun_pembangunan'] = $air->tahun_pembangunan;
                $nestedData['status_operasi'] = $air->status_operasi;
                $nestedData['status_pekerjaan'] = $air->status_pekerjaan;
                $nestedData['updated_at'] = $air->updated_at;
                $nestedData['options'] = '
                    <div class="d-flex">
                        <button type="button" 
                            class="btn btn-outline-primary btn-sm me-1 show-airbaku" 
                            data-id="'.$air->id.'" 
                            title="Detail">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    <a href="javascript:void(0)" 
                            class="btn btn-outline-success btn-sm me-1 btn-tambah-foto" 
                            data-id="'.$air->kode_integrasi.'"
                            title="Tambah Foto">
                            <i class="fas fa-camera"></i>
                        </a>
                    </div>';
                $data[] = $nestedData;
            }

            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            ];

            return response()->json($json_data);
        }

        return view('backend.airbaku.index', ['provinces' => $provinces]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'jenis_aset' => 'required|string'
        ]);

        $file = $request->file('file');
        $jenisAset = $request->jenis_aset;

        // array untuk menampung kode_integrasi dari excel
        $importedKode = [];

        // Jalankan import + catat semua kode_integrasi dari Excel
        Excel::import(new class($jenisAset, $importedKode) implements \Maatwebsite\Excel\Concerns\ToModel, \Maatwebsite\Excel\Concerns\WithHeadingRow {
            private $jenisAset;
            private $importedKode;

            public function __construct($jenisAset, &$importedKode)
            {
                $this->jenisAset = $jenisAset;
                $this->importedKode = &$importedKode;
            }

            public function headingRow(): int
            {
                return 2; // header mulai baris ke-2
            }

            public function model(array $row)
            {
                if (empty($row['kode_integrasi']) || empty($row['nama'])) return null;

                // simpan kode integrasi ke array
                $this->importedKode[] = $row['kode_integrasi'];

                // update atau insert ke tabel air_bakus
                return AirBaku::updateOrCreate(
                    ['kode_integrasi' => $row['kode_integrasi']],
                    [
                        'kode_bmn'          => $row['kode_bmn'] ?? '',
                        'nama_aset'         => $row['nama'] ?? '',
                        'jenis_aset'        => $this->jenisAset,
                        'wilayah_sungai'    => $row['wilayah_sungai'] ?? '',
                        'das'               => $row['daerah_aliran_sungai'] ?? '',
                        'province'          => $row['provinsi'] ?? null,
                        'city'              => $row['kabupaten_kota'] ?? null,
                        'district'          => $row['kecamatan'] ?? null,
                        'village'           => $row['kelurahan'] ?? null,
                        'lat'               => $row['latitude'] ?? '',
                        'long'              => $row['longitude'] ?? '',
                        'tahun_pembangunan' => $row['tahun_pembangunan'] ?? '',
                        'status_operasi'    => $row['status_operasi'] ?? '',
                        'status_pekerjaan'  => $row['status_pekerjaan'] ?? '',
                        'sungai'            => $row['sungai'] ?? '',
                        'updated_at'        => now(),
                    ]
                );
            }
        }, $file);

        // Hapus data air baku lama (jenis_aset sama) yang tidak ada di Excel
        AirBaku::where('jenis_aset', $jenisAset)
            ->whereNotIn('kode_integrasi', $importedKode)
            ->delete();

        return redirect()->back()->with('success', "Data Air Baku ($jenisAset) berhasil disinkronkan dengan Excel");
    }

    public function getPhotos($kode_integrasi)
    {
        $airBaku = AirBaku::with('photos')
            ->where('kode_integrasi', $kode_integrasi)
            ->firstOrFail();

        return response()->json(
            $airBaku->photos->map(function ($p) {
                return [
                    'id' => $p->id,
                    'kode_integrasi' => $p->kode_integrasi,
                    'url' => asset('storage/' . $p->file_path),
                ];
            })
        );
    }

    public function photos_store(Request $request)
    {
        $request->validate([
            'file.*' => 'required|mimes:jpg,jpeg,png',
        ], [
            'file.*.mimes' => 'Format file harus JPG atau PNG',
        ]);

        $kodeIntegrasi = $request->kode_integrasi;

        $manager = new ImageManager(new Driver()); // GD atau Imagick

        foreach ($request->file('file') as $file) {
            // buat image
            $image = $manager->read($file->getRealPath());

            // resize kalau terlalu besar (misalnya max width 1920px)
            $image->scaleDown(width: 1920);

            // mulai dengan kualitas 85
            $quality = 85;
            $encoded = $image->encodeByExtension('jpg', quality: $quality);

            // loop turunkan kualitas sampai ukuran <= 2MB
            while (strlen($encoded) > 2 * 1024 * 1024 && $quality > 10) {
                $quality -= 5;
                $encoded = $image->encodeByExtension('jpg', quality: $quality);
            }

            // simpan ke storage (folder khusus air baku)
            $filename = 'air_baku_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, (string) $encoded);

            AirBakuPhoto::create([
                'kode_integrasi' => $kodeIntegrasi,
                'file_path'      => $filename,
            ]);
        }

        return response()->json(['message' => 'Foto berhasil disimpan'], 200);
    }

    public function photos_destroy($id)
    {
        $photo = AirBakuPhoto::findOrFail($id);

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($photo->file_path);

        // Hapus record dari database
        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus'], 200);
    }

    public function api_airbakus(Request $request)
    {
        try {
            $query = AirBaku::query();

            if ($request->has('jenis_aset')) {
                $jenis = $request->jenis_aset;
                if (is_array($jenis)) {
                    $query->whereIn('jenis_aset', $jenis);
                } else {
                    $query->where('jenis_aset', $jenis);
                }
            }

            $dataAirBaku = $query->get();
            return response()->json(AirBakuResource::collection($dataAirBaku), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function api_airbaku_detail($id)
    {
        try {
            $dataAirBaku = AirBaku::with('photos')->findOrFail($id);
            return response()->json($dataAirBaku, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch air baku'], 500);
        }
    }

}

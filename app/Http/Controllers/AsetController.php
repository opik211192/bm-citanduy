<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetPhoto;
use App\Imports\AsetImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use App\Http\Resources\AsetResource;
use Maatwebsite\Excel\Facades\Excel;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;   // atau Imagick kalau server Anda ada extension Imagick

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
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
                5 => 'tahun_mulai_bangunan',
                6 => 'kondisi_infrastruktur',
                7 => 'status_operasi',
                8 => 'updated_at',
            ];

            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDirection = $request->input('order.0.dir');

            $jenisFilter = $request->input('jenis_aset');

            $query = Aset::query();

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
                    ->orWhere('province', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%")
                    ->orWhere('district', 'LIKE', "%{$search}%")
                    ->orWhere('village', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_mulai_bangunan', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_selesai_bangunan', 'LIKE', "%{$search}%")
                    ->orWhere('kondisi_bangunan', 'LIKE', "%{$search}%")
                    ->orWhere('status_operasi', 'LIKE', "%{$search}%")
                    ->orWhere('kondisi_infrastruktur', 'LIKE', "%{$search}%")
                    ->orWhere('updated_at', 'LIKE', "%{$search}%")
                    ;
                });
            }

            $totalData = Aset::count();
            $totalFiltered = $query->count();

            $asets = $query->offset($start)
                ->limit($limit)
                ->orderBy($orderColumn, $orderDirection)
                ->get();

            $data = [];
            foreach ($asets as $index => $aset) {
                $deleteUrl = route('aset.destroy', $aset->id);

                $nestedData['DT_RowIndex'] = $start + $index + 1;
                $nestedData['nama_aset'] = $aset->nama_aset;
                $nestedData['jenis_aset'] = $aset->jenis_aset;
                $nestedData['kode_bmn'] = $aset->kode_bmn;
                $nestedData['koordinat'] = $aset->lat . ', ' . $aset->long;
                $nestedData['tahun_mulai_bangunan'] = $aset->tahun_mulai_bangunan . ' - ' . $aset->tahun_selesai_bangunan;
                $nestedData['kondisi_infrastruktur'] = $aset->kondisi_infrastruktur;
                $nestedData['status_operasi'] = $aset->status_operasi;
                $nestedData['updated_at'] = $aset->updated_at;
                $nestedData['options'] = '
                    <div class="d-flex">
                        <button type="button" 
                            class="btn btn-outline-primary btn-sm me-1 mr-1 show-aset" 
                            data-id="'.$aset->id.'" 
                            title="Detail">
                            <i class="fas fa-info-circle"></i>
                        </button>
                       <a href="javascript:void(0)" 
                            class="btn btn-outline-success btn-sm me-1 btn-tambah-foto" 
                            data-id="'.$aset->kode_integrasi.'"
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

        return view('backend.aset.index', ['provinces' => $provinces]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dependantDropdownController = new DependantDropdownController();
        $provinces = $dependantDropdownController->provinces();


        return view('backend.aset.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required|string|max:100',
            'no_registrasi' => 'required|string|max:100',
            'kode_bmn' => 'required|string|max:100',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'village_id' => 'required|exists:indonesia_villages,id',
            'lat' => 'required|string|max:100',
            'long' => 'required|string|max:100',
            'utm_x' => 'nullable|string|max:100',
            'utm_y' => 'nullable|string|max:100',
            'tahun_mulai_bangunan' => 'nullable|string|max:4',
            'tahun_selesai_bangunan' => 'nullable|string|max:4',
            'kondisi_bangunan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Simpan data aset
        $aset = Aset::create([
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
            'no_registrasi' => $request->no_registrasi,
            'kode_bmn' => $request->kode_bmn,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'lat' => $request->lat,
            'long' => $request->long,
            'utm_x' => $request->utm_x,
            'utm_y' => $request->utm_y,
            'tahun_mulai_bangunan' => $request->tahun_mulai_bangunan,
            'tahun_selesai_bangunan' => $request->tahun_selesai_bangunan,
            'kondisi_bangunan' => $request->kondisi_bangunan,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan foto-foto jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('aset_photos', 'public');

                AsetPhoto::create([
                    'aset_id' => $aset->id,
                    'file_path' => $path
                ]);
            }
        }

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil disimpan.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $aset = Aset::with('photos')->findOrFail($id);

        return response()->json([
            'id' => $aset->id,
            'kode_integrasi' => $aset->kode_integrasi,
            'kode_bmn' => $aset->kode_bmn,
            'nama_aset' => $aset->nama_aset,
            'jenis_aset' => $aset->jenis_aset,
            'wilayah_sungai' => $aset->wilayah_sungai,
            'das' => $aset->das,
            'province' => $aset->province,
            'city' => $aset->city,
            'district' => $aset->district,
            'village' => $aset->village,
            'lat' => $aset->lat,
            'long' => $aset->long,
            'utm_x' => $aset->utm_x,
            'utm_y' => $aset->utm_y,
            'tahun_mulai_bangunan' => $aset->tahun_mulai_bangunan,
            'tahun_selesai_bangunan' => $aset->tahun_selesai_bangunan,
            'kondisi_bangunan' => $aset->kondisi_bangunan,
            'status_operasi' => $aset->status_operasi,
            'kondisi_infrastruktur' => $aset->kondisi_infrastruktur,
            'photos' => $aset->photos ? $aset->photos->map(function($p) {
                    return ['path' => $p->file_path];
            }) : [],
            'updated_at' => $aset->updated_at,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aset = Aset::with('photos')->findOrFail($id);

        $provinces = \Indonesia::allProvinces();

        $kota1 = \Indonesia::findCity($aset->city_id);
        $cities = City::where('province_code', $kota1->province->code)->pluck('name', 'id');

        $kecamatan1 = \Indonesia::findDistrict($aset->district_id);
        $districts = District::where('city_code', $kecamatan1->city->code)->pluck('name', 'id');

        $desa1 = \Indonesia::findVillage($aset->village_id);
        $villages = Village::where('district_code', $desa1->district->code)->pluck('name', 'id');

        return view('backend.aset.edit', compact('aset', 'provinces', 'cities', 'districts', 'villages'));
    }


    //hapus foto ketika edit
    public function hapusFoto($id)
    {
        $foto = AsetPhoto::findOrFail($id);
        
        if (Storage::disk('public')->exists($foto->file_path)) {
            Storage::disk('public')->delete($foto->file_path);
        }
        
        $foto->delete();
        
        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required',
            'no_registrasi' => 'required',
            'kode_bmn' => 'required',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'village_id' => 'required|exists:indonesia_villages,id',
            'lat' => 'required',
            'long' => 'required',
            'utm_x' => 'nullable',
            'utm_y' => 'nullable',
            'tahun_mulai_bangunan' => 'nullable|numeric|min:1950|max:2100',
            'tahun_selesai_bangunan' => 'nullable|numeric|min:1950|max:2100',
            'kondisi_bangunan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $aset = Aset::findOrFail($id);
        $aset->update($request->except('photos'));

        // Simpan foto baru jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('aset_photos', 'public');
                $aset->photos()->create(['file_path' => $path]);
            }
        }

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aset $aset)
    {
        $aset = Aset::with('photos')->findOrFail($aset->id);

        foreach ($aset->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }


    public function api_asets(Request $request)
    {
    
        try {
            $query = Aset::query();

             if ($request->has('jenis_aset')) {
                $jenis = $request->jenis_aset;
                if (is_array($jenis)) {
                    $query->whereIn('jenis_aset', $jenis);
                } else {
                    $query->where('jenis_aset', $jenis);
                }
            }

            $dataAset = $query->get();
            return response()->json(AsetResource::collection($dataAset), 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function api_asset_detail($id)
    {
        try {
            $dataAset = Aset::with('photos')->findOrFail($id);
            return response()->json($dataAset, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch benchmark'], 500);
        }
    }

    public function print($id)
    {
        $asset = Aset::with('photos')->findOrFail($id);

        return view('backend.aset.detail', ['asset' => $asset]);
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

                // update atau insert
                return \App\Models\Aset::updateOrCreate(
                    ['kode_integrasi' => $row['kode_integrasi']],
                    [
                        'kode_bmn'       => $row['kode_bmn'] ?? '',
                        'nama_aset'      => $row['nama'] ?? '',
                        'jenis_aset'     => $this->jenisAset,
                        'wilayah_sungai' => $row['wilayah_sungai'] ?? '',
                        'das'            => $row['daerah_aliran_sungai'] ?? '',
                        'province'       => $row['provinsi'] ?? null,
                        'city'           => $row['kabupaten_kota'] ?? null,
                        'district'       => $row['kecamatan'] ?? null,
                        'village'        => $row['kelurahan'] ?? null,
                        'lat'            => $row['latitude'] ?? '',
                        'long'           => $row['longitude'] ?? '',
                        'tahun_mulai_bangunan'   => $row['tahun_mulai_pembangunan'] ?? '',
                        'tahun_selesai_bangunan' => $row['tahun_selesai_pembangunan'] ?? '',
                        'kondisi_bangunan'       => $row['kondisi_bangunan'] ?? '',
                        'status_operasi'         => $row['status_operasi'] ?? '',
                        'kondisi_infrastruktur'  => $row['kondisi_infrastruktur'] ?? '',
                        'updated_at'             => now(),
                    ]
                );
            }
        }, $file);

        // Hapus data aset lama (jenis_aset sama) yang tidak ada di Excel
        \App\Models\Aset::where('jenis_aset', $jenisAset)
            ->whereNotIn('kode_integrasi', $importedKode)
            ->delete();

        return redirect()->back()->with('success', "Data $jenisAset berhasil disinkronkan dengan Excel");
    }

    public function getPhotos($kode_integrasi)
    {
        $aset = Aset::with('photos')
            ->where('kode_integrasi', $kode_integrasi)
            ->firstOrFail();

        return response()->json(
            $aset->photos->map(function ($p) {
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

            // simpan ke storage
            $filename = 'aset_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, (string) $encoded);

            AsetPhoto::create([
                'kode_integrasi' => $kodeIntegrasi,
                'file_path' => $filename,
            ]);
        }

        return response()->json(['message' => 'Foto berhasil disimpan'], 200);
    }



    public function photos_destroy($id)
    {
        $photo = AsetPhoto::findOrFail($id);

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($photo->file_path);

        // Hapus record dari database
        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus'], 200);
    }


}

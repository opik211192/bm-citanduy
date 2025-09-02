<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use App\Http\Resources\AsetResource;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $dependantDropdownController = new DependantDropdownController();
    //     $provinces = $dependantDropdownController->provinces();

    //     if ($request->ajax()) {
    //         $columns = [
    //             0 => 'id',
    //             1 => 'nama_aset',
    //             2 => 'jenis_aset',
    //             3 => 'no_registrasi',
    //             4 => 'kode_bmn',
    //             5 => 'koordinat',
    //             6 => 'tahun_mulai_bangunan',
    //             7 => 'kondisi_bangunan',
    //             8 => 'keterangan',
    //         ];

    //         $totalData = Aset::count();
    //         $totalFiltered = $totalData;

    //         $limit = $request->input('length');
    //         $start = $request->input('start');
    //         $orderColumn = $columns[$request->input('order.0.column')];
    //         $orderDirection = $request->input('order.0.dir');

    //          // ambil filter
    //         $jenisFilter = $request->input('jenis_aset');

    //         $query = Aset::query();

    //         if (!empty($jenisFilter)) {
    //             $query->where('jenis_aset', $jenisFilter);
    //         }

    //         if (empty($request->input('search.value'))) {
    //             $asets = Aset::offset($start)
    //                 ->limit($limit)
    //                 ->orderBy($orderColumn, $orderDirection)
    //                 ->get();
    //         } else {
    //             $search = $request->input('search.value');

    //             $asets = Aset::where('nama_aset', 'LIKE', "%{$search}%")
    //                 ->orWhere('jenis_aset', 'LIKE', "%{$search}%")
    //                 ->orWhere('no_registrasi', 'LIKE', "%{$search}%")
    //                 ->orWhere('kode_bmn', 'LIKE', "%{$search}%")
    //                 ->orWhere('lat', 'LIKE', "%{$search}%")
    //                 ->orWhere('tahun_mulai_bangunan', 'LIKE', "%{$search}%")
    //                 ->orWhere('kondisi_bangunan', 'LIKE', "%{$search}%")
    //                 ->orWhere('keterangan', 'LIKE', "%{$search}%")
    //                 ->offset($start)
    //                 ->limit($limit)
    //                 ->orderBy($orderColumn, $orderDirection)
    //                 ->get();

    //             $totalFiltered = Aset::where('nama_aset', 'LIKE', "%{$search}%")
    //                 ->orWhere('jenis_aset', 'LIKE', "%{$search}%")
    //                 ->orWhere('no_registrasi', 'LIKE', "%{$search}%")
    //                 ->orWhere('kode_bmn', 'LIKE', "%{$search}%")
    //                 ->orWhere('lat', 'LIKE', "%{$search}%")
    //                 ->orWhere('tahun_mulai_bangunan', 'LIKE', "%{$search}%")
    //                 ->orWhere('kondisi_bangunan', 'LIKE', "%{$search}%")
    //                 ->orWhere('keterangan', 'LIKE', "%{$search}%")
    //                 ->count();
    //         }

    //         $data = [];
    //         if (!empty($asets)) {
    //             foreach ($asets as $index => $aset) {
    //                 $deleteUrl = route('aset.destroy', $aset->id);

    //                 $nestedData['DT_RowIndex'] = $start + $index + 1;
    //                 $nestedData['nama_aset'] = $aset->nama_aset;
    //                 $nestedData['jenis_aset'] = $aset->jenis_aset;
    //                 $nestedData['no_registrasi'] = $aset->no_registrasi;
    //                 $nestedData['kode_bmn'] = $aset->kode_bmn;
    //                 $nestedData['koordinat'] = $aset->lat . ', ' . $aset->long;
    //                 $nestedData['tahun_mulai_bangunan'] = $aset->tahun_mulai_bangunan . ' - ' . $aset->tahun_selesai_bangunan;
    //                 $nestedData['kondisi_bangunan'] = $aset->kondisi_bangunan;
    //                 $nestedData['keterangan'] = $aset->keterangan;
    //                 $nestedData['options'] = '
    //                     <div class="d-flex">
    //                         <button type="button" 
    //                             class="btn btn-outline-primary btn-sm me-1 show-aset mr-1" 
    //                             data-id="'.$aset->id.'" 
    //                             title="Detail">
    //                             <i class="fas fa-info-circle"></i>
    //                         </button>

    //                         <a href="'.route('aset.edit', $aset->id).'" 
    //                             class="btn btn-outline-primary btn-sm me-1 mr-1" 
    //                             title="Edit">
    //                             <i class="fas fa-edit"></i>
    //                         </a>

    //                         <form action="'.$deleteUrl.'" 
    //                             method="POST" 
    //                             onsubmit="return confirm(\'Yakin ingin hapus?\')" 
    //                             style="display:inline;">
    //                             '.csrf_field().method_field("DELETE").'
    //                             <button type="submit" class="btn btn-outline-primary btn-sm" title="Hapus">
    //                                 <i class="fas fa-trash"></i>
    //                             </button>
    //                         </form>
    //                     </div>';
    //                 $data[] = $nestedData;
    //             }
    //         }

    //         $json_data = [
    //             "draw" => intval($request->input('draw')),
    //             "recordsTotal" => intval($totalData),
    //             "recordsFiltered" => intval($totalFiltered),
    //             "data" => $data,
    //         ];

    //         return response()->json($json_data);
    //     }

    //     return view('backend.aset.index', ['provinces' => $provinces]);
    // }
    public function index(Request $request)
{
    $dependantDropdownController = new DependantDropdownController();
    $provinces = $dependantDropdownController->provinces();

    if ($request->ajax()) {
        $columns = [
            0 => 'id',
            1 => 'nama_aset',
            2 => 'jenis_aset',
            3 => 'no_registrasi',
            4 => 'kode_bmn',
            5 => 'koordinat',
            6 => 'tahun_mulai_bangunan',
            7 => 'kondisi_bangunan',
            8 => 'keterangan',
        ];

        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumn = $columns[$request->input('order.0.column')];
        $orderDirection = $request->input('order.0.dir');

        // ambil filter dropdown
        $jenisFilter = $request->input('jenis_aset');

        // base query
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
                  ->orWhere('no_registrasi', 'LIKE', "%{$search}%")
                  ->orWhere('kode_bmn', 'LIKE', "%{$search}%")
                  ->orWhere('lat', 'LIKE', "%{$search}%")
                  ->orWhere('tahun_mulai_bangunan', 'LIKE', "%{$search}%")
                  ->orWhere('kondisi_bangunan', 'LIKE', "%{$search}%")
                  ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        // total data (tanpa filter)
        $totalData = Aset::count();

        // total data (dengan filter + search)
        $totalFiltered = $query->count();

        // ambil data paginasi
        $asets = $query->offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        // siapkan data untuk datatable
        $data = [];
        foreach ($asets as $index => $aset) {
            $deleteUrl = route('aset.destroy', $aset->id);

            $nestedData['DT_RowIndex'] = $start + $index + 1;
            $nestedData['nama_aset'] = $aset->nama_aset;
            $nestedData['jenis_aset'] = $aset->jenis_aset;
            $nestedData['no_registrasi'] = $aset->no_registrasi;
            $nestedData['kode_bmn'] = $aset->kode_bmn;
            $nestedData['koordinat'] = $aset->lat . ', ' . $aset->long;
            $nestedData['tahun_mulai_bangunan'] = $aset->tahun_mulai_bangunan . ' - ' . $aset->tahun_selesai_bangunan;
            $nestedData['kondisi_bangunan'] = $aset->kondisi_bangunan;
            $nestedData['keterangan'] = $aset->keterangan;
            $nestedData['options'] = '
                <div class="d-flex">
                    <button type="button" 
                        class="btn btn-outline-primary btn-sm me-1 show-aset mr-1" 
                        data-id="'.$aset->id.'" 
                        title="Detail">
                        <i class="fas fa-info-circle"></i>
                    </button>

                    <a href="'.route('aset.edit', $aset->id).'" 
                        class="btn btn-outline-primary btn-sm me-1 mr-1" 
                        title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="'.$deleteUrl.'" 
                        method="POST" 
                        onsubmit="return confirm(\'Yakin ingin hapus?\')" 
                        style="display:inline;">
                        '.csrf_field().method_field("DELETE").'
                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
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
            'nama_aset' => $aset->nama_aset,
            'jenis_aset' => $aset->jenis_aset,
            'no_registrasi' => $aset->no_registrasi,
            'kode_bmn' => $aset->kode_bmn,
            'province' => $aset->province->name,
            'city' => $aset->city->name,
            'district' => $aset->district->name,
            'village' => $aset->village->name,
            'lat' => $aset->lat,
            'long' => $aset->long,
            'tahun_mulai_bangunan' => $aset->tahun_mulai_bangunan,
            'tahun_selesai_bangunan' => $aset->tahun_selesai_bangunan,
            'kondisi_bangunan' => $aset->kondisi_bangunan,
            'keterangan' => $aset->keterangan,
            'photos' => $aset->photos->map(function($p) {
                return ['path' => $p->file_path];
            }),
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
}

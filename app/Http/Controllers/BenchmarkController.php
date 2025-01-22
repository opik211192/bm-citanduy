<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Benchmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use App\Http\Resources\BenchmarkResource;
use Barryvdh\DomPDF\Facade\Pdf;


class BenchmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

public function index(Request $request)
{
     $dependantDropdownController = new DependantDropdownController();

        // Mendapatkan daftar provinsi
        $provinces = $dependantDropdownController->provinces();

        if ($request->ajax()) {
            $columns = [
                0 => 'id',
                1 => 'kode_bm',
                2 => 'no_registrasi',
                3 => 'Nama_pekerjaan',
                4 => 'keterangan',
            ];

            $totalData = Benchmark::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDirection = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $benchmarks = Benchmark::offset($start)
                    ->limit($limit)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $search = $request->input('search.value');

                $benchmarks = Benchmark::where('kode_bm', 'LIKE', "%{$search}%")
                    ->orWhere('no_registrasi', 'LIKE', "%{$search}%")
                    ->orWhere('nama_pekerjaan', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy('id', 'desc')
                    ->get();

                $totalFiltered = Benchmark::where('kode_bm', 'LIKE', "%{$search}%")
                    ->orWhere('no_registrasi', 'LIKE', "%{$search}%")
                    ->orWhere('nama_pekerjaan', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($benchmarks)) {
                foreach ($benchmarks as $index => $benchmark) {
                    // $showUrl = route('benchmark.show', $benchmark->id);
                    // $editUrl = route('benchmark.edit', $benchmark->id);
                    $deleteUrl = route('benchmark.destroy', $benchmark->id);
                    
                    $nestedData['DT_RowIndex'] = $start + $index + 1;
                    $nestedData['id'] = $benchmark->id;
                    $nestedData['kode_bm'] = $benchmark->kode_bm;
                    $nestedData['no_registrasi'] = $benchmark->no_registrasi;
                    $nestedData['nama_pekerjaan'] = $benchmark->nama_pekerjaan;
                    $nestedData['keterangan'] = $benchmark->keterangan;
                    $nestedData['options'] = '<div class="d-flex">' .
                        '<a href="' .route('benchmark.edit', $benchmark->id). '" class="btn btn-info btn-sm" data-toggle="Edit" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;' .
                        '<form action=" ' .route('benchmark.destroy', $benchmark->id). '" method="POST">' .
                        csrf_field() .
                        method_field("DELETE") .
                        '<button type="submit" class="btn btn-danger btn-sm" data-toggle="Edit" data-placement="top" title="Hapus" onclick="return confirm(\'Are You Sure Want to Delete?\')"><i class="fas fa-trash"></i></button>' .
                        '</form>' .
                        '</div>';

                    $data[] = $nestedData; 
                }
            }

            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            ];

            return response()->json($json_data);
        }
         return view('backend.benchmark.index', ['provinces' => $provinces]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Instance dari DependantDropdownController
        $dependantDropdownController = new DependantDropdownController();

        // Mendapatkan daftar provinsi
        $provinces = $dependantDropdownController->provinces();
        return view('backend.benchmark.create', ['provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
{
    // Validasi data yang diterima dari request
    $validatedData = $request->validate([
        'kode_bm' => 'required',
        'no_registrasi' => 'required',
        'nama_pekerjaan' => 'required',
        'province_id' => 'required',
        'city_id' => 'required',
        'district_id' => 'required',
        'village_id' => 'required',
        'utm_x' => 'required',
        'utm_y' => 'required',
        'zone' => 'required',
        'lat' => 'required',
        'long' => 'required',
        'tinggi_orthometrik' => 'nullable',
        'tinggi_elipsoid' => 'nullable',
        'keterangan' => 'nullable|string',
        'sketsa' => 'nullable',
        'foto' => 'nullable',
    ]);

    // Mengupload sketsa
    if ($request->hasFile('sketsa')) {
        $sketsaName = $validatedData['kode_bm'].'_'.uniqid().'_'.$request->file('sketsa')->getClientOriginalName();
        $sketsaPath = $request->file('sketsa')->storeAs('img/sketsa', $sketsaName, 'public');
        $validatedData['sketsa'] = $sketsaName;
    }else{
        $validatedData['sketsa'] = 'no_image.png';
    }

    // Mengupload foto
    if ($request->hasFile('foto')) {
        $fotoName = $validatedData['kode_bm'].'_'.uniqid().'_'.$request->file('foto')->getClientOriginalName();
        $fotoPath = $request->file('foto')->storeAs('img/foto', $fotoName, 'public');
        $validatedData['foto'] = $fotoName;
    }else{
        $validatedData['foto'] = 'no_image.png';
    }
 

    // Membuat benchmark baru
    $benchmark = Benchmark::create([
        'kode_bm' => $validatedData['kode_bm'],
        'no_registrasi' => $validatedData['no_registrasi'],
        'nama_pekerjaan' => $validatedData['nama_pekerjaan'],   
        'province_id' => $validatedData['province_id'],
        'city_id' => $validatedData['city_id'],
        'district_id' => $validatedData['district_id'],
        'village_id' => $validatedData['village_id'],
        'utm_x' => $validatedData['utm_x'],
        'utm_y' => $validatedData['utm_y'],
        'zone' => $validatedData['zone'],
        'lat' => $validatedData['lat'],
        'long' => $validatedData['long'],
        'tinggi_orthometrik' => $validatedData['tinggi_orthometrik'],
        'tinggi_elipsoid' => $validatedData['tinggi_elipsoid'],
        'keterangan' => $validatedData['keterangan'],
    ]);

    // Membuat upload
    Upload::create([
        'benchmark_id' => $benchmark->id,
        'sketsa' => $validatedData['sketsa'],
        'foto' => $validatedData['foto'],
    ]);

    return redirect()->route('benchmark.index')->with('success', 'Data benchmark berhasil ditambahkan');
}

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Benchmark  $benchmark
     * @return \Illuminate\Http\Response
     */
    public function show(Benchmark $benchmark)
    { 
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Benchmark  $benchmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Benchmark $benchmark)
    {
        $upload = $benchmark->uploads;

        $provinces = \Indonesia::allProvinces();

        // Ambil kota berdasarkan ID kota bench$benchmark
        $kota1 = \Indonesia::findCity($benchmark->city_id, $with = null);
        $cities = City::where('province_code', '=', $kota1->province->code)->get();

        // Ambil kecamatan berdasarkan ID kecamatan bench$benchmark
        $kecamatan1 = \Indonesia::findDistrict($benchmark->district_id, $with = null);
        $districts = District::where('city_code', '=', $kecamatan1->city->code)->get();

        // Ambil desa berdasarkan ID desa bench$benchmark
        $desa1 = \Indonesia::findVillage($benchmark->village_id, $with = null);
        $villages = Village::where('district_code', '=', $desa1->district->code)->get();

        $sketsaUrl = $upload && $upload->sketsa ? asset('storage/img/sketsa/' . $upload->sketsa) : null;
        $fotoUrl = $upload && $upload->foto ? asset('storage/img/foto/' . $upload->foto) : null;

        //dd($sketsaUrl, $fotoUrl);
       
        return view('backend.benchmark.edit', [
            'benchmark' => $benchmark,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'sketsaUrl' => $sketsaUrl,
            'fotoUrl' => $fotoUrl
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Benchmark  $benchmark
     * @return \Illuminate\Http\Response
     */
 public function update(Request $request, Benchmark $benchmark)
{
    // Validasi data yang diterima dari request
    $validatedData = $request->validate([
        'kode_bm' => 'required',
        'no_registrasi' => 'required',
        'nama_pekerjaan' => 'required',
        'province_id' => 'required',
        'city_id' => 'required',
        'district_id' => 'required',
        'village_id' => 'required',
        'utm_x' => 'required',
        'utm_y' => 'required',
        'zone' => 'required',
        'lat' => 'required',
        'long' => 'required',
        'tinggi_orthometrik' => 'nullable',
        'tinggi_elipsoid' => 'nullable',
        'keterangan' => 'nullable|string',
        'sketsa' => 'nullable',
        'foto' => 'nullable',
    ]);

      // Mengupdate data benchmark
    $benchmark->update([
        'kode_bm' => $validatedData['kode_bm'],
        'no_registrasi' => $validatedData['no_registrasi'],
        'nama_pekerjaan' => $validatedData['nama_pekerjaan'],
        'province_id' => $validatedData['province_id'],
        'city_id' => $validatedData['city_id'],
        'district_id' => $validatedData['district_id'],
        'village_id' => $validatedData['village_id'],
        'utm_x' => $validatedData['utm_x'],
        'utm_y' => $validatedData['utm_y'],
        'zone' => $validatedData['zone'],
        'lat' => $validatedData['lat'],
        'long' => $validatedData['long'],
        'tinggi_orthometrik' => $validatedData['tinggi_orthometrik'],
        'tinggi_elipsoid' => $validatedData['tinggi_elipsoid'],
        'keterangan' => $validatedData['keterangan'],
    ]);



    // Mengelola sketsa
    if ($request->hasFile('sketsa')) {
        $sketsaName = $validatedData['kode_bm'].'_'.uniqid().'_'.$request->file('sketsa')->getClientOriginalName();
        $sketsaPath = $request->file('sketsa')->storeAs('img/sketsa', $sketsaName, 'public');
        
        // Hapus sketsa lama jika ada
       $oldSketsa = Upload::where('benchmark_id', $benchmark->id)->value('sketsa');
       if ($oldSketsa && $oldSketsa !== 'no_image.png' && Storage::disk('public')->exists('img/sketsa/' . $oldSketsa)) {
            Storage::disk('public')->delete('img/sketsa/' . $oldSketsa);
       }

        
        // Simpan sketsa baru
        Upload::updateOrCreate(
            ['benchmark_id' => $benchmark->id],
            ['sketsa' => $sketsaName]
        );
    }
    
    // Mengelola foto
    if ($request->hasFile('foto')) {
        $fotoName = $validatedData['kode_bm'].'_'.uniqid().'_'.$request->file('foto')->getClientOriginalName();
        $fotoPath = $request->file('foto')->storeAs('img/foto', $fotoName, 'public');
        
        // Hapus foto lama jika ada
        $oldFoto = Upload::where('benchmark_id', $benchmark->id)->value('foto');
        if ($oldFoto && $oldFoto !== 'no_image.png' && Storage::disk('public')->exists('img/foto/' . $oldFoto)) {
            Storage::disk('public')->delete('img/foto/' . $oldFoto);
        }

        // Simpan foto baru
        Upload::updateOrCreate(
            ['benchmark_id' => $benchmark->id],
            ['foto' => $fotoName]
        );
        
    }

    return redirect()->route('benchmark.index')->with('success', 'Data benchmark berhasil diperbarui');
}


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Benchmark  $benchmark
     * @return \Illuminate\Http\Response
     */
  public function destroy(Benchmark $benchmark)
    {
        
        try {
            $benchmark_id = $benchmark->id;

            // Ambil data upload berdasarkan benchmark id
            $upload = Upload::where('benchmark_id', $benchmark_id)->first();

            if ($upload) {
                // Ambil nama file foto dan sketsa
                $fotoName = $upload->foto;
                $sketsaName = $upload->sketsa;

                // Buat path lengkap ke file foto dan sketsa
                //ieu can bener
                $fotoPath = storage_path('app/public/img/foto/' . $fotoName);
                $sketsaPath = storage_path('app/public/img/sketsa/' . $sketsaName);

               // dd($fotoPath);
                // Hapus foto jika ada
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }

                // Hapus sketsa jika ada
                if (file_exists($sketsaPath)) {
                    unlink($sketsaPath);
                }
            }

            // Hapus benchmark
            $benchmark->delete();
            
            return redirect()->route('benchmark.index')->with('success', 'Data benchmark berhasil dihapus');
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan
            Log::error($e->getMessage());
            return redirect()->route('benchmark.index')->with('error', 'Terjadi kesalahan saat menghapus data benchmark');
        }
    }


    public function api_benchmark()
    {
        try {
            $dataBenchmark = Benchmark::all();
            return response()->json(BenchmarkResource::collection($dataBenchmark), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch benchmarks'], 500);
        }
    }


    public function api_benchmark_detail($id)
    {
        try {
            $dataBenchmark = Benchmark::find($id);
            return response()->json($dataBenchmark, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch benchmark'], 500);
        }
    }

    public function print($id)
    {
        $benchmark = Benchmark::find($id);
        return view('backend.benchmark.detail', ['benchmark' => $benchmark]);
    }

    public function download($id)
    {
        $benchmark = Benchmark::find($id);
        $pdf = PDF::loadView('backend.benchmark.print', ['benchmark' => $benchmark]);
        return $pdf->stream();
    }
}

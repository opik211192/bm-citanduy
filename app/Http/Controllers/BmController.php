<?php

namespace App\Http\Controllers;

use App\Models\Bm;
use App\Models\BmPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BmImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Bm::with('photos')->latest()->get();

        // Ambil daftar nama pekerjaan unik untuk filter dropdown
        $pekerjaanList = Bm::select('nama_pekerjaan')
            ->distinct()
            ->orderBy('nama_pekerjaan')
            ->pluck('nama_pekerjaan');

        return view('backend.bm.index', compact('data', 'pekerjaanList'));
    }

    /**
     * Preview: baca nama-nama sheet dari file Excel
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheetNames = $spreadsheet->getSheetNames();

            return response()->json([
                'success' => true,
                'sheets' => $sheetNames,
                'total_sheets' => count($sheetNames),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file Excel: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Import data BM dari sheet-sheet yang dipilih
     * Behavior: updateOrCreate — data lama tetap, data baru bertambah
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'sheets' => 'required|array|min:1',
            'sheets.*' => 'required|string',
        ]);

        try {
            $selectedSheets = $request->input('sheets');
            $import = new BmImport($selectedSheets);

            Excel::import($import, $request->file('file'));

            $totalImported = $import->getTotalImported();
            $totalSheets = count($selectedSheets);

            return response()->json([
                'success' => true,
                'message' => "Import berhasil! {$totalImported} data dari {$totalSheets} sheet.",
                'total_imported' => $totalImported,
                'total_sheets' => $totalSheets,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update BM data (keterangan, dll)
     */
    public function update(Request $request, $id)
    {
        $bm = Bm::findOrFail($id);

        $bm->update([
            'kode_bm'           => $request->input('kode_bm', $bm->kode_bm),
            'nama_pekerjaan'    => $request->input('nama_pekerjaan', $bm->nama_pekerjaan),
            'provinsi'          => $request->input('provinsi', $bm->provinsi),
            'kota'              => $request->input('kota', $bm->kota),
            'kecamatan'         => $request->input('kecamatan', $bm->kecamatan),
            'desa'              => $request->input('desa', $bm->desa),
            'utm_x'             => $request->input('utm_x', $bm->utm_x),
            'utm_y'             => $request->input('utm_y', $bm->utm_y),
            'tinggi_orthometrik'=> $request->input('tinggi_orthometrik', $bm->tinggi_orthometrik),
            'keterangan'        => $request->input('keterangan', $bm->keterangan),
            'nfc_id'            => $request->input('nfc_id', $bm->nfc_id),
            'latitude'          => $request->input('latitude', $bm->latitude),
            'longitude'         => $request->input('longitude', $bm->longitude),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data BM berhasil diupdate.',
        ]);
    }

    /**
     * Get photos for a BM
     */
    public function getPhotos($id)
    {
        $bm = Bm::with('photos')->findOrFail($id);

        return response()->json(
            $bm->photos->map(function ($p) {
                return [
                    'id' => $p->id,
                    'bm_id' => $p->bm_id,
                    'url' => asset('storage/' . $p->file_path),
                ];
            })
        );
    }

    /**
     * Upload photos for a BM (multiple)
     */
    public function photosStore(Request $request)
    {
        $request->validate([
            'bm_id' => 'required|exists:bms,id',
            'file.*' => 'required|mimes:jpg,jpeg,png',
        ], [
            'file.*.mimes' => 'Format file harus JPG atau PNG',
        ]);

        $bmId = $request->bm_id;
        $manager = new ImageManager(new Driver());

        foreach ($request->file('file') as $file) {
            // Baca image
            $image = $manager->read($file->getRealPath());

            // Resize kalau terlalu besar (max width 1920px)
            $image->scaleDown(width: 1920);

            // Compress mulai kualitas 85
            $quality = 85;
            $encoded = $image->encodeByExtension('jpg', quality: $quality);

            // Loop turunkan kualitas sampai <= 2MB
            while (strlen($encoded) > 2 * 1024 * 1024 && $quality > 10) {
                $quality -= 5;
                $encoded = $image->encodeByExtension('jpg', quality: $quality);
            }

            // Simpan ke storage
            $filename = 'bm_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, (string) $encoded);

            BmPhoto::create([
                'bm_id' => $bmId,
                'file_path' => $filename,
            ]);
        }

        return response()->json(['message' => 'Foto berhasil diupload!'], 200);
    }

    /**
     * Delete a BM photo
     */
    public function photosDestroy($id)
    {
        $photo = BmPhoto::findOrFail($id);

        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $photo->delete();

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bm = Bm::with('photos')->findOrFail($id);

        return view('backend.bm.detail', compact('bm'));
    }

    /**
     * Public page for QR/NFC scan (no auth)
     */
    public function showPublic($kode_bm)
    {
        $bm = Bm::with('photos')->where('kode_bm', $kode_bm)->firstOrFail();

        return view('backend.bm.show_data_bm', compact('bm'));
    }

    /**
     * QR Code print page (single)
     */
    public function qrCode($id)
    {
        $bm = Bm::findOrFail($id);
        $bms = collect([$bm]);

        return view('backend.bm.qr_print', compact('bms'));
    }

    /**
     * QR Code print page (all)
     */
    public function qrCodeAll()
    {
        $bms = Bm::orderBy('nama_pekerjaan')->orderBy('kode_bm')->get();

        return view('backend.bm.qr_print', compact('bms'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bm $bm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bm $bm)
    {
        //
    }
}

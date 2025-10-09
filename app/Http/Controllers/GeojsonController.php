<?php

namespace App\Http\Controllers;

use App\Models\Geojson;
use Illuminate\Http\Request;

class GeojsonController extends Controller
{

    public function index()
    {
        $files = Geojson::all();
        return view('backend.geojson.index', compact('files'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimetypes:application/json,text/plain,application/geo+json'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/geojson', $fileName);

        Geojson::create([
            'name' => $request->name,
            'file_path' => str_replace('public/', 'storage/', $filePath),
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $data = Geojson::findOrFail($id);
        $data->name = $request->name;

        if ($request->hasFile('file')) {
            if ($data->file_path && file_exists(public_path($data->file_path))) {
                unlink(public_path($data->file_path));
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/geojson', $fileName);
            $data->file_path = str_replace('public/', 'storage/', $filePath);
        }

        $data->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $data = Geojson::findOrFail($id);
        if ($data->file_path && file_exists(public_path($data->file_path))) {
            unlink(public_path($data->file_path));
        }
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function dasIndex()
    {
        $pathDas = public_path('js/batasDas.geojson');
        return view('backend.geojson.das.index', compact('pathDas'));
    }

    public function uploadDas(Request $request)
    {
       $request->validate([
            'file_das' => 'required|file|mimes:json,geojson|max:5120',
        ]);

        $file = $request->file('file_das');
        $filename = 'batasdas.geojson';
        $targetPath = public_path('js/' . $filename);

        // Hapus file lama kalau ada
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }

        // Simpan file baru
        $file->move(public_path('js'), $filename);

        return response()->json([
            'success' => true,
            'message' => 'File GeoJSON berhasil diperbarui.',
            'url' => asset('js/' . $filename)
        ]);
    }

    public function sungaiIndex()
    {

    }
}

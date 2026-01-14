<?php

namespace App\Http\Controllers;

use App\Models\Konsultan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class KonsultanController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Konsultan::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('logo', function ($row) {
                    if ($row->logo) {
                        return '<img src="' . asset('storage/img/konsultan/' . $row->logo) . '" width="60">';
                    }
                    return '-';
                })

                ->addColumn('aksi', function ($row) {
                    return '
                        <button class="btn btn-warning btn-sm btn-edit" data-id="' . $row->id . '">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })

                ->rawColumns(['logo', 'aksi'])
                ->make(true);
        }

        return view('backend.benchmark.data-konsultan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp'=> 'nullable|string|max:50',
            'email'  => 'nullable|email',
            'logo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama'    => $request->nama,
            'alamat'  => $request->alamat,
            'no_telp' => $request->no_telp,
            'email'   => $request->email,
        ];

        // UPLOAD LOGO
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/img/konsultan', $filename);
            $data['logo'] = $filename;
        }

        Konsultan::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        return response()->json(
            Konsultan::findOrFail($id)
        );
    }

    public function destroy($id)
    {
        $konsultan = Konsultan::findOrFail($id);

        if ($konsultan->logo) {
            Storage::delete('public/img/konsultan/' . $konsultan->logo);
        }

        $konsultan->delete();

        return response()->json(['success' => true]);
    }
}

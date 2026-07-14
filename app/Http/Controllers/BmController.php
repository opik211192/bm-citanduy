<?php

namespace App\Http\Controllers;

use App\Models\Bm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BmImport;

class BmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Bm::latest()->get();
        return view('backend.bm.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bm  $bm
     * @return \Illuminate\Http\Response
     */
    public function show(Bm $bm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bm  $bm
     * @return \Illuminate\Http\Response
     */
    public function edit(Bm $bm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\bm  $bm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bm $bm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bm  $bm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bm $bm)
    {
        //
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new BmImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimport');
    }
}

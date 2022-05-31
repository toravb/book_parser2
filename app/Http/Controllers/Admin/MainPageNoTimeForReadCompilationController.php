<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compilation;
use Illuminate\Http\Request;

class MainPageNoTimeForReadCompilationController extends Controller
{
    public function index(Compilation $compilation)
    {
        $audiobooks = $compilation
            ->with(['audioBooks:id,title,year_id', 'audioBooks.year:id,year'])
            ->where('location', 2)
            ->get();
//        dd($audiobooks);
        return view('admin.compilations.no_time_for_read.index', compact('audiobooks'));

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

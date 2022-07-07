<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compilation;
use Illuminate\Http\Request;

class CompilationController extends Controller
{
    public function index(Compilation $compilation)
    {
        $compilations = $compilation->compilationsForAdmin();
        return view('admin.compilations.index', compact('compilations'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}

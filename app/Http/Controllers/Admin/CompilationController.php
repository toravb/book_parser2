<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\CompilationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompilationRequest;
use App\Models\Compilation;
use Illuminate\Http\Request;

class CompilationController extends Controller
{
    public function index(Compilation $compilation, CompilationFilter $filter)
    {
        $compilations = $compilation->compilationsForAdmin()->filter($filter)->get();
//        dd($compilations);
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

    public function edit($compilation, Compilation $compilations)
    {
        $compilation = $compilations->compilationsForAdmin()->findOrFail($compilation);

        return view('admin.compilations.edit', compact('compilation'));

    }

    public function update(UpdateCompilationRequest $request, Compilation $compilation)
    {
//        dd($request);
        $compilation->saveFromRequest($request);

        return redirect()->route('admin.compilations.edit', $compilation)->with('success', 'Подборка успешно обновлена!');
    }

    public function destroy($id)
    {
    }
}

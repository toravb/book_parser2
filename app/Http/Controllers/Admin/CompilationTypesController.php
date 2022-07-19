<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Select2SearchRequest;
use App\Models\CompilationType;
use Illuminate\Http\Request;

class CompilationTypesController extends Controller
{
    public function index(CompilationType $type, Select2SearchRequest $request)
    {
        $types = $type->when($request->search, function ($query) use ($request) {
            $query->where('name', 'LIKE', "{$request->search}%");
        })->paginate(25);

        if ($request->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($types);
        }

        return view('admin.compilation-types.index', compact('types'));
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

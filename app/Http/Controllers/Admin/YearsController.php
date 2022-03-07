<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Select2SearchRequest;
use App\Http\Requests\StoreYearRequest;
use App\Http\Requests\UpdateYearRequest;
use App\Models\Year;

class YearsController extends Controller
{
    public function index(Year $years, Select2SearchRequest $request)
    {
        $years = $years->when($request->search, function ($q) use ($request) {
            $q->where('year', 'LIKE', "{$request->search}%");
        })->paginate(25);

        if ($request->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($years);
        }

        return view('admin.years.index', compact('years'));
    }

    public function store(StoreYearRequest $request, Year $year)
    {
        $year->saveFromRequest($request);

        return redirect()->route('admin.years.index')->with('success', 'Год издания успешно добавлен!');
    }

    public function edit(Year $year)
    {
        return view('admin.years.edit', compact('year'));
    }

    public function update(UpdateYearRequest $request, Year $year)
    {
        $year->saveFromRequest($request);

        return redirect()->route('admin.years.edit', $year)->with('success', 'Год издания успешно обновлён!');
    }

    public function destroy(Year $year)
    {
        $year->delete();

        return ApiAnswerService::redirect(route('admin.years.index'));
    }
}

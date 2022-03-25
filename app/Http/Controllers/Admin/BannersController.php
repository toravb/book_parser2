<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\BannerFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Routing\Controller;

class BannersController extends Controller
{
    public function index(Banner $banners, BannerFilter $filter)
    {
        $banners = $banners->dataForAdminPanel()
            ->filter($filter)
            ->paginate(5)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(StoreBannerRequest $request, Banner $banner)
    {
        $banner->saveFromRequest($request);

        return redirect()->route('admin.banners.edit', $banner)->with('success', 'Баннер успешно создан!');
    }

    public function edit($banner, Banner $banners)
    {
        $banner = $banners->dataForAdminPanel()->findOrFail($banner);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner->saveFromRequest($request);

        return redirect()->route('admin.banners.edit', $banner)->with('success', 'Баннер обновлен!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return ApiAnswerService::redirect(route('admin.banners.index'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\BannerFilter;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BannersController extends Controller
{
    public function index(Banner $banners, BannerFilter $filter)
    {
        $banners = $banners->dataForAdminPanel()
            ->filter($filter)
            ->paginate(5)->withQueryString();
//        dd($banners);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Banner $banner)
    {
        //
    }

    public function edit($banner, Banner $banners)
    {
        $banner = $banners->dataForAdminPanel()->findOrFail($banner);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        if ($request->image_remove and $banner->image) {
            \Storage::delete($banner->image);
            $banner->image = null;
        }

        $banner->saveFromRequest($request);

        return redirect()->route('admin.banners.edit', $banner)->with('success', 'Баннер обновлена!');
    }

    public function destroy(Banner $banner)
    {
        //
    }
}

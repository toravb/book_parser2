<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Http\Requests\Admin\StoreBannerRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use LaravelIdea\Helper\App\Models\_IH_Book_QB;
use Storage;
use Symfony\Component\HttpFoundation\Request;

class Banner extends Model
{
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function dataForAdminPanel(): Model|Builder|Book|_IH_Book_QB
    {
        return $this->with('genres');
    }

    public function saveFromRequest(StoreBannerRequest $request)
    {
        $this->is_active = (bool)$request->is_active;
        $this->name = $request->name;

        if ($request->image) {
            if ($this->image) \Storage::delete($this->image);

            $this->image = \Storage::put('banners', $request->image);
        }

        $this->text = $request->text ?? '';
        $this->link = $request->link;
        $this->content = $request->alt_content;
        $this->save();

        $this->genres()->sync($request->genres_id);
    }
}

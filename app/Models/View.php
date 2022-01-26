<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable =
        [
            'user_id',
            'ip_address',
            'viewable_id',
            'viewable_type'
        ];

    public function viewable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function addView(?int $user_id, ?string $ip_address, int $viewable_id, string $viewable_type)
    {
        $this->firstOrCreate(
            [
                'user_id' => $user_id,
                'ip_address' => $ip_address,
                'viewable_id' => $viewable_id,
                'viewable_type' => $viewable_type
            ]
        );

    }
}

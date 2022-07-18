<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class View extends Model
{
    protected $fillable =
        [
            'user_id',
            'ip_address',
            'viewable_id',
            'viewable_type'
        ];

    public function viewable(): MorphTo
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

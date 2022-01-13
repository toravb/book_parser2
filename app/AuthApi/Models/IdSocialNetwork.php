<?php

namespace App\AuthApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IdSocialNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_id',
        'vkontakte_id',
        'odnoklassniki_id',
        'yandex_id',
        'user_id'
    ];

    public function updateOrCreateNetworks(string $column, int $userId, int $id): void
    {
        $this->updateOrCreate([
            'user_id' => $userId
        ],
            [
                $column => $id
            ]
        );
    }


}

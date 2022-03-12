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
        'user_id',
        'temp_token',
        'token_valid_until'
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

    public function updateAfterBinding(string $column, int $userId, int $id): void
    {
        $this->where('user_id', $userId)->update([
            $column => $id,
            'temp_token' => null,
            'token_valid_until' => null
        ]);
    }
}

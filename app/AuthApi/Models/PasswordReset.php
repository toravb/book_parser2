<?php

namespace App\AuthApi\Models;

use App\Services\GenerateUniqueTokenService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    public function create(string $email): PasswordReset
    {
        $this->email = $email;
        $this->token = GenerateUniqueTokenService::createTokenWithoutUserId();
        $this->created_at = Carbon::now();
        $this->save();
        return $this;
    }

    public function deleteRecord(string $email): void
    {
        $this->where('email', $email)->delete();
    }
}

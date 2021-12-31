<?php

namespace App\Models;

use App\Services\GenerateUniqueTokenService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function create($fields)
    {
        $user = new static();
        $user->fill($fields);
        $user->generatePassword($fields);
        $user->save();

        return $user;
    }

    public function createUser(string $email=null, string $password = null, string $name = null, bool $needVerify = false): User
    {
        $this->email = mb_strtolower($email);
        $this->password = Hash::make($password);
        $this->name = $name;
        if($needVerify) {
            $this->verify_token = GenerateUniqueTokenService::createTokenWithoutUserId();
        }
        $this->save();
        return $this;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->generatePassword($fields);
        $this->save();
    }

    private function generatePassword($fields)
    {
        if (isset($fields['password']) && $fields['password'] != null) {
            $this->password = bcrypt($fields['password']);
        }
    }
}

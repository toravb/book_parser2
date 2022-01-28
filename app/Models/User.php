<?php

namespace App\Models;

use App\Services\GenerateUniqueTokenService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        'verify_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value): ?string
    {
        if (isset($value) and Storage::exists($value)) {
            $value = Storage::url($value);
        } else {
            $value = null;
        }

        return $value;
    }

    public static function create($fields)
    {
        $user = new static();
        $user->fill($fields);
        $user->generatePassword($fields);
        $user->save();

        return $user;
    }

    public function createUser(string $email = null, string $password = null, string $name = null, bool $needVerify = false): User
    {
        $this->email = mb_strtolower($email);
        $this->password = Hash::make($password);
        $this->name = $name;
        if ($needVerify) {
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

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function bookComments()
    {
        return $this->belongsToMany(BookComment::class);
    }

    public function bookLikes()
    {
        return $this->belongsToMany(BookLike::class);
    }

    public function bookStatuses()
    {
        return $this->belongsToMany(Book::class);
    }

    public function compilations()
    {
        return $this->hasMany(Compilation::class);
    }

    public function compilationUsers()
    {
        return $this->hasMany(CompilationUser::class);
    }

    public function readingSettings()
    {
        return $this->hasMany(ReadingSettings::class);
    }

}

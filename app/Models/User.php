<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public static function create($fields){
        $user = new static();
        $user->fill($fields);
        $user->generatePassword($fields);
        $user->save();

        return $user;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->generatePassword($fields);
        $this->save();
    }

    private function generatePassword($fields){
        if (isset($fields['password']) && $fields['password'] != null){
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
        return $this->belongsToMany(BookUser::class);
    }

}

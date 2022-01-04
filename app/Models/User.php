<?php

namespace App\Models;

//

use Laravel\Passport\HasApiTokens;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\CanResetPassword;

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
    /**
     * @var mixed
     */

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailForgotPasswordNotification($token));
    }

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


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompilationUser extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'compilation_user';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function bookCompilations()
    {
        return $this->belongsToMany(BookCompilation::class);
    }

    public function compilations()
    {
        return $this->hasMany(Compilation::class);
    }

    public function addToFavorite(int $userId, int $compilationId)
    {
        $this->user_id = $userId;
        $this->compilation_id = $compilationId;
        $this->save();
        return $this;
    }

    public function removeFromFavorite(int $compilationId)
    {
        return $this->where('user_id', Auth::id())
            ->where('compilation_id', $compilationId)
            ->delete();
    }

}

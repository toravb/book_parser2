<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompilationUser extends Model
{
    use HasFactory;

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

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompilationType extends Model
{
    use HasFactory;

    protected $table = 'compilation_type';

    public function compilations(){
        return $this->hasMany(Compilation::class);
    }

}

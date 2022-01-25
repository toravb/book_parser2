<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarAuthors extends Model
{
    use HasFactory;
    public function authors()
    {
        return $this->belongsTo(Author::class, 'author_id_to');
    }
}

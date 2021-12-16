<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioParsingStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'status_id',
        'doParse',
        'status',
        'created_at',
        'updated_at',
        'last_parsing',
        'min_count',
        'max_count',
        'paused'
    ];
}

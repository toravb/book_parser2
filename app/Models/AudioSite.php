<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioSite extends Model
{
    use HasFactory;

    public function defaultStatus()
    {
        return $this->hasOne(
            AudioParsingStatus::class,
            'site_id',
            'id',
        )->where('status_id', '=', 0);
    }

    public function authorStatus()
    {
        return $this->hasOne(
            AudioParsingStatus::class,
            'site_id',
            'id',
        )->where('status_id', '=', 1);
    }

    public function bookStatus()
    {
        return $this->hasOne(
            AudioParsingStatus::class,
            'site_id',
            'id',
        )->where('status_id', '=', 2);
    }

    public function imageStatus()
    {
        return $this->hasOne(
            AudioParsingStatus::class,
            'site_id',
            'id',
        )->where('status_id', '=', 3);
    }

    public function audioBookStatus()
    {
        return $this->hasOne(
            AudioParsingStatus::class,
            'site_id',
            'id',
        )->where('status_id', '=', 4);
    }
}

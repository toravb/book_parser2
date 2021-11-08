<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'link',
        'doParse'
    ];

    public static function create($fields){
        $page_link = new static();
        $page_link->fill($fields);
        $page_link->save();

        return $page_link;
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
}

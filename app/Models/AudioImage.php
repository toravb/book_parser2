<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class AudioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'book_id',
        'doParse'
    ];

    public function deleteImageFile()
    {
        if($this->link) {
            \Storage::delete($this->link);
        }
    }

    public function delete()
    {
        $this->deleteImageFile();

        parent::delete();
    }

    public static function create($fields)
    {
        $image = new static();
        $image->fill($fields);
        $image->save();

        return $image;
    }

    public function saveFromUploadedFile(UploadedFile $image)
    {
        $this->link = \Storage::put('audio-books-covers', $image);
    }

    public function book()
    {
        return $this->belongsTo(
            AudioBook::class,
            'book_id',
            'id',
        );
    }
}

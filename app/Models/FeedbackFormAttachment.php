<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FeedbackFormAttachment extends Model
{
    public function toArray()
    {
        if ($this->storage_path and \Storage::exists($this->storage_path)) {
            $this->storage_path = \Storage::url($this->storage_path);
        }

        return parent::toArray();
    }

    public function create(UploadedFile $file)
    {
        $this->file_name = $file->getClientOriginalName();
        $this->storage_path = $file->store('support-feedback-attachments');

        $this->save();
    }

    public function feedbackForm(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FeedbackForm::class);
    }
}

<?php

namespace App\Models;

use App\Api\Http\Requests\FeedbackFormRequest;
use App\Api\Services\ApiAnswerService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FeedbackForm extends Model
{
    use HasFactory;

    public $fillable = ['name', 'email', 'subject', 'message'];

    public function saveFromRequest(FeedbackFormRequest $request)
    {
        $this->email = $request->email;
        $this->name = $request->name;
        $this->subject = $request->subject;
        $this->message = $request->message;
        $this->save();

        foreach ($request->attachments ?? [] as $attachment) {
            $attachmentRow = new FeedbackFormAttachment();
            $attachmentRow->feedback_form_id = $this->id;

            $attachmentRow->create($attachment);
        }
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeedbackFormAttachment::class);
    }
}

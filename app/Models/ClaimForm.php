<?php

namespace App\Models;

use App\Api\Http\Requests\ClaimFormRequest;
use App\Api\Http\Requests\FeedbackFormRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimForm extends Model
{
    use HasFactory;

    public $fillable = [ 'subject', 'link_source', 'link_content', 'name', 'email',
        'agreement', 'copyright_holder', 'interaction'];

    public function create(ClaimFormRequest $request)
    {
        $this->subject = $request->subject;
        $this->link_source = $request->link_source;
        $this->link_content = $request->link_content;
        $this->name = $request->name;
        $this->email = $request->email;
        $this->agreement = $request->agreement;
        $this->copyright_holder = $request->copyright_holder;
        $this->interaction = $request->interaction;
        $this->save();
    }

}

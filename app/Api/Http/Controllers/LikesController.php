<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{

    public function likeToBook(Book $book)
    {
        $user = Auth::user();
        $params = [
            'user_id'=>$user->id,
            'likeable_id'=>$book->id,
            'likeable_type'=>"book"];
        Like::firstOrCreate($params);
        return back();
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Like $like)
    {
        //
    }



    public function destroy(Like $like)
    {
        //
    }
}

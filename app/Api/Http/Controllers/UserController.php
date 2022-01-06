<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;


class UserController extends Controller
{
    public function destroy(User $user)
    {

            $posts = Post::where('user_id', $user->id)->pluck('id');
            \DB::transaction(function () use ($posts, $user) {
                \DB::table('comments')->whereIn('post_id', $posts)->delete();
                \DB::table('posts')->where('user_id', $user->id)->delete();
                \DB::table('users')->where('id', $user->id)->delete();
            });

    }
}

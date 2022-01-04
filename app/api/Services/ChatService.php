<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ChatService
{

    public function findCommonChat($from, $to)
    {
        $findChatsForSender = DB::table('chat_user')
            ->select('chat_id')
            ->where('user_id', $from)
            ->pluck('chat_id')->toArray();

        $findChatsForReceiver = DB::table('chat_user')
            ->select('chat_id')
            ->where('user_id', $to)
            ->pluck('chat_id')->toArray();

        $findChatForBothUser = array_intersect($findChatsForSender, $findChatsForReceiver);

        return $findChatForBothUser;
    }
}

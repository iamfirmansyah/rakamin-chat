<?php

namespace App\Services;

use App\Chat;
use App\ChatMessage;
use App\Helpers\PublicIdUtil;

class ChatService
{
	public static function doChat($chat)
    {
        $parentChat = ChatService::firstChat($chat);

        $create = new ChatMessage();
        $create->chat_id   = $parentChat->id;
        $create->from      = $chat->from;
        $create->message   = $chat->message;
        $create->file      = $chat->file ?? null;
        $create->file_type = $chat->file_type ?? null;
        $create->save();

        return $create;
    }

    private static function firstChat($chat)
    {
        $check = Chat::where('phone', $chat->phone)->where('from', $chat->from)->where('to', $chat->to)->first();

        if(empty($check)){
            $create = new Chat();
            $create->id    = PublicIdUtil::unique('chats', 'id');
            $create->phone = $chat->phone;
            $create->from  = $chat->from;
            $create->to = $chat->to;
            $create->save();

            return $create;
        }

        return $check;

    }

    public static function deleteMessage($id)
    {
        $delete = ChatMessage::where('id', $id)->delete();
    }

    public static function deleteMessageFromChat($id)
    {
        $delete = ChatMessage::where('chat_id', $id)->delete();
    }

    public static function deleteChat($id)
    {
        $delete = Chat::where('id', $id)->delete();
    }

    public static function convertFile($id, $file, $type)
    {
        if (null === $file) {
            return null;
        }

        $path = env('CHAT_FOLDER') ?? 'public/attachment/';

        $folder = base_path($path);

        if (!file_exists($folder)) mkdir($folder);

        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . sha1($id . "_" . date('YmdHis')) . "." . $extension;
        $file->move($folder, $filename);

        $folder = str_replace('public', '', env('CHAT_FOLDER'));

        $url = (env('APP_URL') ?? 'url_not_found') .'/'. $folder . $filename;

        return $url;
    }
}

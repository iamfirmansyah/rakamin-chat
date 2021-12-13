<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatMessage;
use App\Helpers\PaginatorFormatter;
use App\Helpers\Response;
use App\Services\ChatService;
use App\User;
use Illuminate\Http\Request;
use stdClass;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->per_page;
        $page  = $request->page;

        $getChatFromMe = Chat::where('from', auth()->user()->id)->pluck('id')->toArray();
        $getChatToMe   = Chat::where('to', auth()->user()->id)->pluck('id')->toArray();

        $mergeID = array_merge($getChatFromMe, $getChatToMe);

        $chats = Chat::whereIn('id', $mergeID)->orderBy('created_at', 'desc');

        $paginate = PaginatorFormatter::format($chats->paginate($limit));

        return Response::send(200, $paginate);
    }

    public function create(Request $request)
    {
        $validate = $this->validateError($request, [
            'phone'   => 'required',
            'to'      => 'required',
            'message' => 'required',
        ]);

        if ($validate !== true) return $validate;

        $validateData = User::where('phone', $request->phone)->where('id', $request->to)->first();

        if(empty($validateData)){
            return Response::message(Response::UNKNOWN_RESOURCE);
        }

        $parseData = new stdClass;
        $parseData->phone   = $request->phone;
        $parseData->to      = $request->to;
        $parseData->from    = auth()->user()->id;
        $parseData->message = $request->message;

        $chat = ChatService::doChat($parseData);

        return Response::send(200, $chat);
    }

    public function detail(Request $request, $chatID)
    {
        $limit = $request->per_page;

        $chat = Chat::where('id', $chatID)->first();

        if(empty($chat)){
            return Response::message(Response::RESOURCE_NOT_FOUND);
        }

        $message = ChatMessage::where('chat_id', $chatID)->orderBy('created_at','desc')->paginate($limit);

        $payload = [
            'detail_chat' => $chat,
            'message'     => $message,
        ];

        return Response::send(200, $payload);
    }

    public function deleteChat($id)
    {
        $delete = ChatService::deleteChat($id);
        $deleteMessage = ChatService::deleteMessageFromChat($id);

        return Response::send(204);
    }

    public function deleteMessage($id)
    {
        $delete = ChatService::deleteMessage($id);

        return Response::send(204);
    }
}

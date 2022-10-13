<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use GrahamCampbell\ResultType\Success;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatHistoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $userChats = ChatHistory::create([
                ChatHistory::USER_FROM => $request->from_user_id,
                ChatHistory::USER_TO => $request->sender_user_id,
                ChatHistory::MESSAGES => $request->message
            ]);
            return response()->json(['status' => 'success', 'myData' => $userChats]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }

    public function countMessage(Request $request)
    {
        try {
            $unReadMsg = ChatHistory::select(DB::raw('count(id) as message_count'), ChatHistory::USER_FROM)
                ->where(ChatHistory::STATUS, 'new')
                ->where(ChatHistory::USER_TO, $request->from_to_user_id)
                ->groupBy(ChatHistory::USER_FROM)
                ->get();
            return $unReadMsg;
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }


    public function showMessges(Request $request)
    {
        try {
            $loadUnreadMsg = ChatHistory::select(ChatHistory::USER_TO, ChatHistory::USER_FROM, ChatHistory::MESSAGES, ChatHistory::CREATED_AT)
                ->where(ChatHistory::STATUS, 'new')
                ->where(ChatHistory::USER_FROM, $request->from_user_id)
                ->get();
            return $loadUnreadMsg;
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }

    public function loadAllMsg(Request $request)
    {
        try {
            $loadAllMsg = ChatHistory::select(ChatHistory::USER_TO, ChatHistory::USER_FROM, ChatHistory::MESSAGES, ChatHistory::CREATED_AT, 'id')
                ->where(ChatHistory::STATUS, 'seen')
                ->where(function ($query) use ($request) {
                    $query->where(ChatHistory::USER_FROM, $request->from_user_id)->where(ChatHistory::USER_TO, $request->my_user_id);
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where(ChatHistory::USER_TO, $request->from_user_id)->where(ChatHistory::USER_FROM, $request->my_user_id);
                })
                ->get();
                // dd($loadAllMsg);
            return $loadAllMsg;
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }


    public function deleteMsg(Request $request)
    {
        try {
            $deleteMsg = ChatHistory::destroy($request->message_id);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }

    public function readMsg(Request $request)
    {
        try {
            $readMsg = ChatHistory::where(ChatHistory::USER_FROM, $request->from_user_id)
                ->where(ChatHistory::USER_TO, $request->my_user_id)
                ->update([ChatHistory::STATUS => 'seen']);
            return $readMsg;
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getUsers()
    {
        return response()->json(User::all());
    }
    public function fetchMessages($userId1, $userId2)
    {
        $messages = Message::where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId2)->where('receiver_id', $userId1);
        })->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }


    public function sendMessage(Request $request)
    {
        $channelName = $this->generateChannelName($request->sender_id, $request->receiver_id);
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'fichier' => 'nullable',
            'message' => 'nullable',
        ]);

        $fileName = null;
        $path = null;

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/message', $fileName);
        }

        $message = Message::create([
            'sender_id' => $validatedData['sender_id'],
            'receiver_id' => $validatedData['receiver_id'],
            'message' => $validatedData['message'],
            'fichierName' => $fileName
        ]);
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true]);
        $pusher->trigger($channelName, 'message-sent', $message);
        return response()->json($message);
    }
    private function generateChannelName($userId1, $userId2)
    {
        $userIds = [$userId1, $userId2];
        sort($userIds);
        return 'Chat-' . implode('-', $userIds);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/public/message/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }
}

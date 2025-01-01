<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Messagegroupe;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;

class MessagegroupeController extends Controller
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

    public function sendMessage(Request $request, $groupId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'content' => 'nullable',
            'fichier' => 'nullable',
        ]);

        $fileName = null;
        $path = null;


        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/messageGroup', $fileName);
        }

        $group = Group::findOrFail($groupId);
        $user = User::findOrFail($validated['user_id']);
        $message = $group->messagegroupes()->create([
            'content' => $validated['content'],
            'user_id' => $validated['user_id'],
            'fichierName' => $fileName,
        ]);

        $data = ['message' => $message, 'user' => $user];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' =>
            env('PUSHER_APP_CLUSTER'), 'useTLS' => true]
        );
        $pusher->trigger($groupId, 'message-sent', $data);

        return response()->json($message);
    }

    public function getMessages($groupId)
    {
        $group = Group::findOrFail($groupId);
        return response()->json($group->messagegroupes()->with('user')->get());
    }
    public function deleteMessage($groupId, $messageId)
    {
        $message = Messagegroupe::where('group_id', $groupId)
            ->where('id', $messageId)
            ->firstOrFail();

        if ($message->fichierName) {
            $filePath = storage_path('app/public/messageGroup/' . $message->fichierName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $message->delete();
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true]
        );
        $pusher->trigger($groupId, 'message-deleted', ['messageId' => $messageId]);
        return response()->json(['message' => 'Message supprimé avec succès']);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/public/messageGroup/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }
}

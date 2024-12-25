<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

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
        ]);
        $group = Group::findOrFail($groupId);
        $message = $group->messagegroupes()->create([
            'content' => $validated['content'],
            'user_id' => $validated['user_id'],
        ]);
        return response()->json($message);
    }

    public function getMessages($groupId)
    {
        $group = Group::findOrFail($groupId);
        return response()->json($group->messagegroupes()->with('user')->get());
    }
}

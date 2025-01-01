<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
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

    public function createGroup(Request $request)
    {
        $validated = $request->validate(['name' => 'nullable', 'user_id' => 'required|exists:users,id']);
        $group = Group::create([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
        ]);
        return response()->json($group);
    }

    public function addMember(Request $request, $groupId)
    {
        $validated = $request->validate(['user_id' => 'required|exists:users,id']);
        $group = Group::findOrFail($groupId);
        if (!$group->members()->where('user_id', $validated['user_id'])->exists()) {
            $group->members()->attach($validated['user_id']);
            return response()->json(['message' => 'Membre ajouté avec succès !']);
        }
        return response()->json(['message' => 'Cet utilisateur est déjà membre !'], 400);
    }

    public function getGroupsByUser($userId)
    {
        $user = User::findOrFail($userId);
        $groups = $user->groups()->with('admin')->get();

        return response()->json($groups);
    }


    public function deleteGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        $group->delete();

        return response()->json(['message' => 'Groupe supprimé avec succès']);
    }

    public function removeMember($groupId, $userId)
    {
        $group = Group::findOrFail($groupId);
        $group->members()->detach($userId);

        return response()->json(['message' => 'Membre retiré avec succès']);
    }

    public function getAllUsersByGroupId($groupId)
    {
        $group = Group::with('members')->findOrFail($groupId);

        return response()->json(
            $group
        );
    }

    public function putGroupe(Request $request, string $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);
        $filteredData = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $group->update($filteredData);
        return response()->json($group, 200);
    }
}

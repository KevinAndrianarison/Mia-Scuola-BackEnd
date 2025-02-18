<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mentionacceuil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentionacceuilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $files = Mentionacceuil::all();
        return response()->json($files, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nomMention' => 'nullable',
            'photo' => 'nullable',
            'descriptionMention' => 'nullable',
        ]);
        $fileName = null;
        $path = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/mentions', $fileName);
        }

        $fileRecord = Mentionacceuil::create([
            'nomMention' => $validatedData['nomMention'],
            'descriptionMention' => $validatedData['descriptionMention'],
            'photo_name' => $fileName
        ]);

        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $file = Mentionacceuil::find($id);

        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(['error' => 'Mention introuvable !'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'nomMention' => 'nullable',
            'photo' => 'nullable',
            'descriptionMention' => 'nullable',
        ]);

        $fileRecord = Mentionacceuil::findOrFail($id);
        if ($request->hasFile('photo')) {
            if ($fileRecord->photo_name) {
                Storage::delete('public/mentions/' . $fileRecord->photo_name);
            }
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/mentions', $fileName);
            $validatedData['photo_name'] = $fileName;
        }
        $fileRecord->update($validatedData);
        return response()->json($fileRecord, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //        
        $fileRecord = Mentionacceuil::find($id);

        if ($fileRecord) {
            if ($fileRecord->photo_name) {
                Storage::disk('public')->delete('mentions/' . $fileRecord->photo_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Mention supprimée !'], 200);
        } else {
            return response()->json(['error' => 'Mention introuvable !'], 404);
        }
    }
}

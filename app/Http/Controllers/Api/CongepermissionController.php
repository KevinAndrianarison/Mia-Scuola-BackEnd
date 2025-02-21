<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Congepermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CongepermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(
            Congepermission::with(['user'])
                ->get(),
            200
        );
    }

    public function getAnnonceByIdUser($user_id)
    {
        //
        return response()->json(
            Congepermission::where('user_id', $user_id)
                ->with(['user'])
                ->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'description' => 'nullable',
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'type' => 'nullable',
            'category' => 'nullable',
            'status' => 'nullable',
            'fichier' => 'nullable',
            'user_id' => 'required|exists:users,id',

        ]);
        $fileName = null;
        $path = null;
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/conge', $fileName);
        }

        $fileRecord = Congepermission::create([
            'description' => $validatedData['description'],
            'dateDebut' => $validatedData['dateDebut'],
            'dateFin' => $validatedData['dateFin'],
            'type' => $validatedData['type'],
            'category' => $validatedData['category'],
            'user_id' => $validatedData['user_id'],
            'status' => $validatedData['status'],
            'fichier_nom' => $fileName
        ]);
        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $categori = Congepermission::with('user')
            ->findOrFail($id);
        return response()->json($categori, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'description' => 'nullable',
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'type' => 'nullable',
            'category' => 'nullable',
            'status' => 'nullable',
            'fichier' => 'nullable',
        ]);
        $fileRecord = Congepermission::findOrFail($id);

        if ($request->hasFile('fichier')) {

            if ($fileRecord->fichier_nom) {
                Congepermission::delete('public/conge/' . $fileRecord->fichier_nom);
            }
            $file = $request->file('fichier');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/conge', $fileName);
            $validatedData['fichier_nom'] = $fileName;
        }

        $fileRecord->update($validatedData);
        return response()->json($fileRecord, 200);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/public/conge/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $fileRecord = Congepermission::find($id);
        if ($fileRecord) {
            if ($fileRecord->fichier_nom) {
                Storage::disk('public')->delete('conge/' . $fileRecord->fichier_nom);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Demande supprimée !'], 200);
        } else {
            return response()->json(['error' => 'Demande introuvable !'], 404);
        }
    }
}

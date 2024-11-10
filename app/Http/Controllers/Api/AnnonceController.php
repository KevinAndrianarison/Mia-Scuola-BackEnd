<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(
            Annonce::with('user')
                ->with('categori')
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
            'titre' => 'nullable',
            'description' => 'nullable',
            'fichier' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'categori_id' => 'required|exists:categoris,id'

        ]);
        $file = $request->file('fichier');
        $fileName = $file->getClientOriginalName();

        $path = $file->storeAs('public/annonce', $fileName);
        $fileRecord = Annonce::create([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'user_id' => $validatedData['user_id'],
            'categori_id' => $validatedData['categori_id'],
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
        $annonce = Annonce::find($id);

        if ($annonce) {
            return response()->json($annonce, 200);
        } else {
            return response()->json(['error' => 'Annonce introuvable !'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'titre' => 'nullable',
            'description' => 'nullable',
            'fichier' => 'nullable',
            'categori_id' => 'nullable|exists:categoris,id'
        ]);
        $fileRecord = Annonce::findOrFail($id);

        if ($request->hasFile('fichier')) {

            if ($fileRecord->fichier_nom) {
                Storage::delete('public/annonce/' . $fileRecord->fichier_nom);
            }
            $file = $request->file('fichier');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/annonce', $fileName);
            $validatedData['fichier_nom'] = $fileName;
        }

        $fileRecord->update($validatedData);
        return response()->json($fileRecord, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $fileRecord = Annonce::find($id);

        if ($fileRecord) {
            if ($fileRecord->fichier_nom) {
                Storage::disk('public')->delete('annonce/' . $fileRecord->fichier_nom);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Annonce supprimé !'], 200);
        } else {
            return response()->json(['error' => 'Annonce introuvable !'], 404);
        }
    }


    public function downloadFile($filename)
    {
        $path = storage_path('app/public/annonce/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }
    public function getAnnonceByIdCategorie($categori_id)
    {
        $annonces = Annonce::where('categori_id', $categori_id)
            ->with('user')
            ->with('categori')
            ->get();

        if ($annonces->isEmpty()) {
            return response()->json(['message' => 'Aucune annonce trouvée pour cette catégorie !'], 404);
        }

        return response()->json($annonces, 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Cour::with('ec')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nom_cours' => 'nullable',
            'description_cours' => 'nullable',
            'categorie_cours' => 'nullable',
            'cours' => 'nullable',
            'ec_id' => 'required|exists:ecs,id'
        ]);
        $file = $request->file('cours');
        $fileName = $file->getClientOriginalName();

        $path = $file->storeAs('public/cours', $fileName);
        $fileRecord = Cour::create([
            'nom_cours' => $validatedData['nom_cours'],
            'description_cours' => $validatedData['description_cours'],
            'categorie_cours' => $validatedData['categorie_cours'],
            'ec_id' => $validatedData['ec_id'],
            'cours_name' => $fileName
        ]);
        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cours = Cour::find($id);

        if ($cours) {
            return response()->json($cours, 200);
        } else {
            return response()->json(['error' => 'Cours introuvable !'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'nom_cours' => 'nullable',
            'description_cours' => 'nullable',
            'categorie_cours' => 'nullable',
            'cours' => 'nullable',
        ]);
        $fileRecord = Cour::findOrFail($id);

        if ($request->hasFile('cours')) {

            if ($fileRecord->cours_name) {
                Storage::delete('public/cours/' . $fileRecord->cours_name);
            }
            $file = $request->file('cours');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/cours', $fileName);
            $validatedData['cours_name'] = $fileName;
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
        $fileRecord = Cour::find($id);

        if ($fileRecord) {
            if ($fileRecord->cours_name) {
                Storage::disk('public')->delete('cours/' . $fileRecord->cours_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Cours supprimé !'], 200);
        } else {
            return response()->json(['error' => 'Cours introuvable !'], 404);
        }
    }

    public function getByIdEC($ec_id)
    {
        $cours = Cour::where('ec_id', $ec_id)->get();
        return response()->json($cours, 200);
    }

    public function downloadCours($filename)
    {
        $path = storage_path('app/public/cours/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }
}

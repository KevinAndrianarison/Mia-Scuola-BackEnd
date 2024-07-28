<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;



class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = Etablissement::all();
        return response()->json($files, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    //
    {
        $validatedData = $request->validate([
            'nom_etab' => 'required|string',
            'slogan_etab' => 'required|string',
            'descri_etab' => 'required|string',
            'abr_etab' => 'required',
            'dateCreation_etab' => 'required|string',
            'logo_etab' => 'required|file|mimes:jpg,png'
        ]);

        $file = $request->file('logo_etab');
        $fileName = $file->getClientOriginalName();

        $path = $file->storeAs('public/etablissement', $fileName);

        $fileRecord = Etablissement::create([
            'nom_etab' => $validatedData['nom_etab'],
            'slogan_etab' => $validatedData['slogan_etab'],
            'descri_etab' => $validatedData['descri_etab'],
            'abr_etab' => $validatedData['abr_etab'],
            'dateCreation_etab' => $validatedData['dateCreation_etab'],
            'logo_etab' => $fileName
        ]);

        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $file = Etablissement::find($id);

        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(['error' => 'Etablissement introuvable !'], 404);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    //
    {
        $validatedData = $request->validate([
            'nom_etab' => 'required|string',
            'slogan_etab' => 'required|string',
            'descri_etab' => 'required|string',
            'abr_etab' => 'required',
            'dateCreation_etab' => 'required|string',
            'logo_etab' => 'required|file|mimes:jpg,png'
        ]);

        $fileRecord = Etablissement::findOrFail($id);

        if ($request->hasFile('logo_etab')) {
            if ($fileRecord->file_name) {
                Storage::delete('public/etablissement/' . $fileRecord->file_name);
            }

            $file = $request->file('logo_etab');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/etablissement', $fileName);

            $validatedData['file_name'] = $fileName;
        }

        $fileRecord->update($validatedData);

        return response()->json($fileRecord, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    //
    {
        $fileRecord = Etablissement::find($id);

        if ($fileRecord) {
            if ($fileRecord->file_name) {
                Storage::disk('public')->delete('etablissement/' . $fileRecord->file_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Etablissement supprimé !'], 200);
        } else {
            return response()->json(['error' => 'Etablissement introuvable !'], 404);
        }
    }
}

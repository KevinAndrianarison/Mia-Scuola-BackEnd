<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Log;
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
            'nom_etab' => 'nullable',
            'slogan_etab' => 'nullable',
            'descri_etab' => 'nullable',
            'ville_etab' => 'nullable',
            'email_etab' => 'nullable',
            'pays_etab' => 'nullable',
            'mdpAppGmail_etab' => 'nullable',
            'codePostal_etab' => 'nullable',
            'abr_etab' => 'nullable',
            'dateCreation_etab' => 'nullable',
            'logo_etab' => 'nullable'
        ]);

        $file = $request->file('logo_etab');
        $fileName = $file->getClientOriginalName();

        $path = $file->storeAs('public/etablissement', $fileName);

        $fileRecord = Etablissement::create([
            'nom_etab' => $validatedData['nom_etab'],
            'slogan_etab' => $validatedData['slogan_etab'],
            'descri_etab' => $validatedData['descri_etab'],
            'abr_etab' => $validatedData['abr_etab'],
            'ville_etab' => $validatedData['ville_etab'],
            'codePostal_etab' => $validatedData['codePostal_etab'],
            'mdpAppGmail_etab' => $validatedData['mdpAppGmail_etab'],
            'pays_etab' => $validatedData['pays_etab'],
            'email_etab' => $validatedData['email_etab'],
            'dateCreation_etab' => $validatedData['dateCreation_etab'],
            'logo_name' => $fileName
        ]);

        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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
    public function update(Request $request, $id)
    //
    {
        $validatedData = $request->validate([
            'nom_etab' => 'nullable',
            'slogan_etab' => 'nullable',
            'descri_etab' => 'nullable',
            'abr_etab' => 'nullable',
            'ville_etab' => 'nullable',
            'email_etab' => 'nullable',
            'codePostal_etab' => 'nullable',
            'mdpAppGmail_etab' => 'nullable',
            'pays_etab' => 'nullable',
            'dateCreation_etab' => 'nullable',
            'logo_etab' => 'nullable'
        ]);
        $fileRecord = Etablissement::findOrFail($id);

        if ($request->hasFile('logo_etab')) {

            if ($fileRecord->logo_name) {
                Storage::delete('public/etablissement/' . $fileRecord->logo_name);
            }
            $file = $request->file('logo_etab');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/etablissement', $fileName);
            $validatedData['logo_name'] = $fileName;
        }

        $fileRecord->update($validatedData);
        return response()->json($fileRecord, 200);
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    //
    {
        $fileRecord = Etablissement::find($id);

        if ($fileRecord) {
            if ($fileRecord->logo_name) {
                Storage::disk('public')->delete('etablissement/' . $fileRecord->logo_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'Etablissement supprimé !'], 200);
        } else {
            return response()->json(['error' => 'Etablissement introuvable !'], 404);
        }
    }

    public function downloadImage($filename)
    {
        $path = storage_path('app/public/etablissement/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->download($path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        }
        return abort(404);
    }
}

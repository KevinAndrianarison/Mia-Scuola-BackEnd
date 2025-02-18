<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acceuilimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcceuilimageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $files = Acceuilimage::all();
        return response()->json($files, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'photoNameOne' => 'nullable',
            'photoNameTwo' => 'nullable',
            'photoNameThree' => 'nullable',
        ]);

        $fileRecord = Acceuilimage::create($validatedData);
        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $file = Acceuilimage::find($id);

        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(['error' => 'Photos introuvable !'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    //     $validatedData = $request->validate([
    //         'photoOne' => 'nullable',
    //         'photoTwo' => 'nullable',
    //         'photoThree' => 'nullable',
    //     ]);

    //     $fileRecord = Acceuilimage::findOrFail($id);

    //     if ($request->photoOne === null && $fileRecord->photoNameOne) {
    //         Storage::delete('public/acceuil/' . $fileRecord->photoNameOne);
    //         $validatedData['photoNameOne'] = null;
    //     }
    //     if ($request->hasFile('photoOne')) {
    //         if ($fileRecord->photoNameOne) {
    //             Storage::delete('public/acceuil/' . $fileRecord->photoNameOne);
    //         }
    //         $file = $request->file('photoOne');
    //         $fileName = $file->getClientOriginalName();
    //         $path = $file->storeAs('public/acceuil', $fileName);
    //         $validatedData['photoNameOne'] = $fileName;
    //     }

    //     if ($request->photoTwo === null && $fileRecord->photoNameTwo) {
    //         Storage::delete('public/acceuil/' . $fileRecord->photoNameTwo);
    //         $validatedData['photoNameTwo'] = null;
    //     }
    //     if ($request->hasFile('photoTwo')) {
    //         if ($fileRecord->photoNameTwo) {
    //             Storage::delete('public/acceuil/' . $fileRecord->photoNameTwo);
    //         }
    //         $file = $request->file('photoTwo');
    //         $fileName = $file->getClientOriginalName();
    //         $path = $file->storeAs('public/acceuil', $fileName);
    //         $validatedData['photoNameTwo'] = $fileName;
    //     }

    //     if ($request->photoThree === null && $fileRecord->photoNameThree) {
    //         Storage::delete('public/acceuil/' . $fileRecord->photoNameThree);
    //         $validatedData['photoNameThree'] = null;
    //     }
    //     if ($request->hasFile('photoThree')) {
    //         if ($fileRecord->photoNameThree) {
    //             Storage::delete('public/acceuil/' . $fileRecord->photoNameThree);
    //         }
    //         $file = $request->file('photoThree');
    //         $fileName = $file->getClientOriginalName();
    //         $path = $file->storeAs('public/acceuil', $fileName);
    //         $validatedData['photoNameThree'] = $fileName;
    //     }

    //     $fileRecord->update($validatedData);
    //     return response()->json($fileRecord, 201);
    // }
    public function update(Request $request, string $id)
{
    $validatedData = $request->validate([
        'photoOne' => 'nullable',
        'photoTwo' => 'nullable',
        'photoThree' => 'nullable',
    ]);

    $fileRecord = Acceuilimage::findOrFail($id);
    if ($request->has('photoOne')) {
        if ($request->photoOne === null && $fileRecord->photoNameOne) {
            Storage::delete('public/acceuil/' . $fileRecord->photoNameOne);
            $validatedData['photoNameOne'] = null;
        } elseif ($request->hasFile('photoOne')) {
            if ($fileRecord->photoNameOne) {
                Storage::delete('public/acceuil/' . $fileRecord->photoNameOne);
            }
            $file = $request->file('photoOne');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/acceuil', $fileName);
            $validatedData['photoNameOne'] = $fileName;
        }
    }
    if ($request->has('photoTwo')) {
        if ($request->photoTwo === null && $fileRecord->photoNameTwo) {
            Storage::delete('public/acceuil/' . $fileRecord->photoNameTwo);
            $validatedData['photoNameTwo'] = null;
        } elseif ($request->hasFile('photoTwo')) {
            if ($fileRecord->photoNameTwo) {
                Storage::delete('public/acceuil/' . $fileRecord->photoNameTwo);
            }
            $file = $request->file('photoTwo');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/acceuil', $fileName);
            $validatedData['photoNameTwo'] = $fileName;
        }
    }
    if ($request->has('photoThree')) {
        if ($request->photoThree === null && $fileRecord->photoNameThree) {
            Storage::delete('public/acceuil/' . $fileRecord->photoNameThree);
            $validatedData['photoNameThree'] = null;
        } elseif ($request->hasFile('photoThree')) {
            if ($fileRecord->photoNameThree) {
                Storage::delete('public/acceuil/' . $fileRecord->photoNameThree);
            }
            $file = $request->file('photoThree');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/acceuil', $fileName);
            $validatedData['photoNameThree'] = $fileName;
        }
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
    }
}

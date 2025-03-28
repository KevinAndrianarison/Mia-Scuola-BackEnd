<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $trans = Transaction::with(['user', 'au'])->get();
        return response()->json($trans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'date' => 'nullable',
            'montant' => 'nullable',
            'type' => 'nullable',
            'description' => 'nullable',
            'categorie' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'au_id' => 'required|exists:aus,id',
            'niveau_id' => 'nullable'
        ]);

        $trans = Transaction::create($validated);
        return response()->json($trans, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $trans = Transaction::with(['user', 'au'])->find($id);
        return response()->json($trans);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $trans = Transaction::find($id);
        $validated = $request->validate([
            'date' => 'nullable',
            'montant' => 'nullable',
            'type' => 'nullable',
            'description' => 'nullable',
            'categorie' => 'nullable',
        ]);

        $trans->update($validated);
        return response()->json($trans);
    }

    public function getByIdAU($au_id,)
    {
        $trans = Transaction::where('au_id', $au_id)
            ->with('au')
            ->with('niveau')
            ->with('au.etablissement')
            ->with('user')
            ->with('user.agentscolarite')
            ->get();
        return response()->json($trans, 200);
    }

    public function getByIdNiveau($niveau_id,)
    {
        $trans = Transaction::where('niveau_id', $niveau_id)
            ->with('au')
            ->with('niveau')
            ->with('au.etablissement')
            ->with('user')
            ->with('user.agentscolarite')
            ->get();
        return response()->json($trans, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $trans = Transaction::find($id);
        $trans->delete();
        return response()->json(['message' => 'Transaction supprimé !']);
    }
}

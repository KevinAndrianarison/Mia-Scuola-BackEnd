<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Admin::with('user')->get(), 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomComplet_admin' => 'nullable',
            'telephone_admin' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $admin = Admin::create($request->all());

        return response()->json($admin, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $admin = Admin::findOrFail($id);
        return response()->json($admin->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $admin = Admin::findOrFail($id);
        $request->validate([
            'nomComplet_admin' => 'nullable',
            'telephone_admin' => 'nullable',
        ]);
        $admin->update($request->all());
        return response()->json($admin, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }

    public function getByUserId($user_id)
    {
        $admin = Admin::where('user_id', $user_id)->with('user')->get();
        return response()->json($admin, 200);
    }

    public function getFirst()
    {
        return response()->json(Admin::with('user')->first(), 200);
    }
}

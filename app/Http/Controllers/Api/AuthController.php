<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $files = User::all();
        return response()->json($files, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'status_user' => 'nullable',
            'email' => 'nullable',
            'validiter_compte' => 'nullable',
        ]);
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $file = User::find($id);

        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(['error' => 'User introuvable !'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status_user' => 'nullable',
            'email' => 'nullable',
            'password' => 'nullable',
            'validiter_compte' => 'nullable',
            'photo' => 'nullable'
        ]);
        $fileRecord = User::findOrFail($id);
        $userEmail = $fileRecord->email;
        if ($request->hasFile('photo')) {
            if ($fileRecord->photo_name) {
                Storage::delete('public/users/' . $fileRecord->photo_name);
            }

            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/users', $fileName);

            $validatedData['photo_name'] = $fileName;
        }
        $fileRecord->update($validatedData);
        if (!empty($validatedData['password'])) {
            $etablissement = Etablissement::first();

            if ($etablissement) {
                $nomEtablissement = $etablissement->nom_etab;
                $emailEtablissement = $etablissement->email_etab;
                $password = $validatedData['password'];

                $messageContent = "Bonjour {$userEmail},\n\n" .
                    "✅ Votre compte a été créé avec succès !\n\n" .
                    "Voici vos nouvelles informations de connexion :\n" .
                    "Email : {$userEmail}\n" .
                    "Mot de passe : {$password}\n\n" .
                    "Cordialement,\n{$nomEtablissement} 🤝";

                config([
                    'mail.mailers.smtp.username' => $emailEtablissement,
                    'mail.mailers.smtp.password' => 'anpa oysz axto vohr',
                    'mail.from.address' => $emailEtablissement,
                    'mail.from.name' => $nomEtablissement,
                ]);

                Mail::raw($messageContent, function ($message) use ($userEmail, $emailEtablissement, $nomEtablissement) {
                    $message->to($userEmail)
                        ->from($emailEtablissement, $nomEtablissement)
                        ->subject("Création de votre compte à {$nomEtablissement}");
                });
            }
        }

        return response()->json($fileRecord, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $fileRecord = User::find($id);

        if ($fileRecord) {
            if ($fileRecord->photo_name) {
                Storage::disk('public')->delete('users/' . $fileRecord->photo_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'User supprimé'], 200);
        } else {
            return response()->json(['error' => 'User introuvable'], 404);
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'status_user' => 'nullable',
            'email' => 'nullable',
            'password' => 'nullable',
            'validiter_compte' => 'nullable',
            'photo' => 'nullable'
        ]);

        $file = $request->file('photo');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('public/users', $fileName);

        $fileRecord = User::create([
            'status_user' => $validatedData['status_user'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'validiter_compte' => $validatedData['validiter_compte'],
            'photo_name' => $fileName
        ]);

        $etablissement = Etablissement::first();

        if ($etablissement) {
            $nomEtablissement = $etablissement->nom_etab;
            $emailEtablissement = $etablissement->email_etab;
            $password = $validatedData['password'];

            $messageContent = "Bonjour {$validatedData['email']},\n\n" .
                "✅ Votre compte a été créé avec succès !\n\n" .
                "Voici vos informations de connexion :\n" .
                "Email : {$validatedData['email']}\n" .
                "Mot de passe : {$password}\n\n" .
                "Cordialement,\n{$nomEtablissement} 🤝";

            config([
                'mail.mailers.smtp.username' => $emailEtablissement,
                'mail.mailers.smtp.password' => 'anpa oysz axto vohr',
                'mail.from.address' => $emailEtablissement,
                'mail.from.name' => $nomEtablissement,
            ]);

            Mail::raw($messageContent, function ($message) use ($validatedData, $emailEtablissement, $nomEtablissement) {
                $message->to($validatedData['email'])
                    ->from($emailEtablissement, $nomEtablissement)
                    ->subject("Création de votre compte à {$nomEtablissement}");
            });
        }

        return response()->json([
            $fileRecord
        ], 201);
    }


    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($validatedData)) {
            return response()->json(['error' => 'Autorisation refusé !'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
    public function profil()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Connexion réussi !']);
    }
}

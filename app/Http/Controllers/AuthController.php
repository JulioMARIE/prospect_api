<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use App\Models\Commercial;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

    public function commercialLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|min:8',
        ]);

        // return $request;

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // return $utilisateur;

        // return Hash::check($request->mot_de_passe,$utilisateur->mot_de_passe);

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return response()->json([
                'successCode' => 0,
                'message' => 'Echec',
            ], 500);
        }

        $commercial = Commercial::where('utilisateur_id', $utilisateur->id)->with('utilisateur')->first();

        $permissions = $commercial->utilisateur->permissions;
        // return $commercial;

        if (!$commercial) {
            return response()->json([
                'successCode' => 0,
                'message' => 'L\'utilisateur n\'est pas un commercial.',
            ], 500);
        }

        $token = $commercial->utilisateur->createToken('commercial', ['*'], now()->addHours(24))->plainTextToken;

        // return $token;

        return response()->json([
            'commercial' => $commercial,
            'permissions' => $permissions,
            'token' => $token
        ]);
    }
    
    public function responsableLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|min:8',
        ]);

        

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // return Hash::check($request->mot_de_passe,$utilisateur->mot_de_passe);

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return response()->json([
                'successCode' => 0,
                'message' => 'Echec',
            ], 500);
        }

        $responsable = Responsable::where('utilisateur_id', $utilisateur->id)->with('utilisateur')->first();

        // return $responsable;

        if (!$responsable) {
            return response()->json([
                'successCode' => 0,
                'message' => 'L\'utilisateur n\'est pas un responsable.',
            ], 500);
        }

        $token = $responsable->utilisateur->createToken('responsable', ['*'], now()->addHours(24))->plainTextToken;

        // return $token;

        return response()->json([
            'responsable' => $responsable,
            'token' => $token
        ]);
    }

    public function logout(Utilisateur $u)
    {
        $u->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function utilisateur(Request $request)
    {
        return $request->utilisateur();
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Reset link sent'], 200);
            } else {
                return response()->json(['message' => trans($status)], 400);
            }
        } catch (\Exception $e) {
            // Log l'erreur
            // \Log::error('Password reset error: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur '. $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request, Utilisateur $utilisateur)
    {
        $request->validate([
            'mot_de_passe_actuel' => 'required',
            'nouveau_mot_de_passe' => 'required|min:8|different:mot_de_passe_actuel',
            'nouveau_mot_de_passe_confirme' => 'required|same:nouveau_mot_de_passe',
        ]);
        
        // return $request->nouveau_mot_de_passe;
        // return !Hash::check($request->mot_de_passe_actuel, $utilisateur->mot_de_passe);

        if (!Hash::check($request->mot_de_passe_actuel, $utilisateur->mot_de_passe)) {
            return response()->json([
                'successCode' => 0,
                'message' => 'Echec',
            ], 500);
        }

            $utilisateur->mot_de_passe = Hash::make($request->nouveau_mot_de_passe);
            $utilisateur->save();

        return response()->json([
            'successCode' => 1,
            'message' => 'Mot de passe changé avec succès.',
        ]);
    }
}

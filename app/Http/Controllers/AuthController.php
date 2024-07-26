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
            throw ValidationException::withMessages([
                'email' => ["Les informations d'identification fournies sont incorrectes."],
            ]);
        }

        $commercial = Commercial::where('utilisateur_id', $utilisateur->id)->with('utilisateur')->first();

        // return $commercial;

        if (!$commercial) {
            throw ValidationException::withMessages([
                'email' => ["L'utilisateur n'est pas un commercial."],
            ]);
        }

        $token = $commercial->utilisateur->createToken('commercial', ['*'], now()->addHours(24))->plainTextToken;

        // return $token;

        return response()->json([
            'commercial' => $commercial,
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
            throw ValidationException::withMessages([
                'email' => ["Les informations d'identification fournies sont incorrectes."],
            ]);
        }

        $responsable = Responsable::where('utilisateur_id', $utilisateur->id)->with('utilisateur')->first();

        // return $responsable;

        if (!$responsable) {
            throw ValidationException::withMessages([
                'email' => ["L'utilisateur n'est pas un responsable."],
            ]);
        }

        $token = $responsable->utilisateur->createToken('responsable', ['*'], now()->addHours(24))->plainTextToken;

        // return $token;

        return response()->json([
            'responsable' => $responsable,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->utilisateur()->currentAccessToken()->delete();
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
            \Log::error('Password reset error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }
}

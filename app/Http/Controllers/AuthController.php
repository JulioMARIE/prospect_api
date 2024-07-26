<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function responsableLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            throw ValidationException::withMessages([
                'email' => ["Les informations d'identification fournies sont incorrectes."],
            ]);
        }

        $responsable = Responsable::where('utilisateur_id', $utilisateur->id)->with('utilisateur')->first();

        if (!$responsable) {
            throw ValidationException::withMessages([
                'email' => ["L'utilisateur n'est pas un responsable."],
            ]);
        }

        $token = $responsable->utilisateur->createToken('responsable', ['*'], now()->addHours(24))->plainTextToken;


        return response()->json([
            'responsable' => $responsable,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' => 'Reset link sent'])
                    : response()->json(['message' => 'Unable to send reset link'], 400);
    }
}

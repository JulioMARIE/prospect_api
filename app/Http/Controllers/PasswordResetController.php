<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;

class PasswordResetController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:utilisateurs,email',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        // Générer un nouveau mot de passe
        $newPassword = $this->generatePassword();

        // Mettre à jour le mot de passe de l'utilisateur
        $user->mot_de_passe = Hash::make($newPassword);
        $user->save();

        // Envoyer le nouveau mot de passe par email
        Mail::to($user->email)->send(new PasswordReset($newPassword));

        return response()->json(['successCode' => 1, 'message' => 'Un nouveau mot de passe a été envoyé à votre adresse email.'], 200);
    }

    private function generatePassword()
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$*()-_+=';

        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

        for ($i = 0; $i < 4; $i++) {
            $allChars = $uppercase . $lowercase . $numbers . $specialChars;
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Commercial;
use App\Models\Quota;
use App\Http\Requests\StoreCommercialRequest;
use App\Http\Requests\UpdateCommercialRequest;
use App\Models\Prospection;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Auth;

class CommercialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('sanctum')->user();
        if($user && isset($user->responsable)) {
            return Commercial::with(['utilisateur', 'quotas'])->get();
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommercialRequest $request)
    {
        // return $request;

        $r = Auth::guard('sanctum')->user()->responsable;
        // return $r;

        if($r)    {
            $newPassword = $this->generatePassword();

            $u = Utilisateur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'mot_de_passe' => Hash::make($newPassword),
            ]);

            $c = Commercial::create([
                'utilisateur_id' => $u->id,
            ]);

            if($c)    {
                Mail::to($u->email)->send(new PasswordReset($newPassword));
                return response()->json([
                    'successCode' => 1,
                    'message' => 'Commercial créé avec succès.',
                ], 200);
                return $c->load('utilisateur');
            }

            return response()->json([
                'successCode' => 0,
                'message' => 'Commercial non créé avec succès.',
            ], 500);
        }
        
        return response()->json([
            'successCode' => 0,
            'message' => 'Utilisateur non responsable',
        ], 500);
        

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

    /**
     * Display the specified resource.
     */
    public function show(Commercial $commercial)
    {
        return $commercial->with('utilisateur');
    }

    public function myprospect(Commercial $commercial)
    {
            return $commercial->prospections()->orderBy('created_at', 'desc')->with('societe', 'suivis')->get();
    }


    public function mesquotas(Commercial $commercial)
    {
        // return $commercial;
            $quotas = $commercial->quotas()->orderBy('created_at', 'desc')->get();
        // return $quotas;

            // foreach ($quotas as $quota) {
            //     // Récupérer les prospections du commercial pour la période du quota
            //     $prospections = Prospection::where('commercial_id', $quota->commercial_id)
            //         ->whereBetween('date_heure', [$quota->date_debut . ' 00:00:00', $quota->date_fin . ' 23:59:59'])
            //         ->count();

            //     // Déterminer le statut
            //     $statut = $prospections >= $quota->nombre_fixe ? 1 : 0;

            //     // Ajouter les informations au quota
            //     $quota->nombre_fait = $prospections;
            //     $quota->statut = $statut;
            // }

            return response()->json($quotas);
            
        }

    public function statutQuotaActuel(Commercial $commercial)
    {
        
        $today = Carbon::today()->format('Y-m-d');

        // return $today;

        // Trouver le quota actuel pour ce commercial
        $quotaActuel = Quota::where('commercial_id', $commercial->id)
            ->where('date_debut', '<=', $today)
            ->where('date_fin', '>=', $today)
            ->first();

        // return $quotaActuel;

        if (!$quotaActuel) {
            return response()->json([
                'nombre_quota_fixe' => 0,
                'nombre_prospections_faites' => 0,
                'date_fin' => "",
            ]);
        }

        // Calculer le nombre de prospections faites pour ce quota
        $prospectionsFaites = Prospection::where('commercial_id', $commercial->id)
            ->whereBetween('date_heure', [
                $quotaActuel->date_debut . ' 00:00:00',
                $quotaActuel->date_fin . ' 23:59:59'
            ])
            ->count();

        return response()->json([
            'nombre_quota_fixe' => $quotaActuel->nombre_fixe,
            'nombre_prospections_faites' => $prospectionsFaites,
            'date_fin' => $quotaActuel->date_fin,
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommercialRequest $request, Commercial $commercial)
    {
        // return $request->all();    
        
         // return $request;

            $u = $commercial->utilisateur;

            if($request->has('nom'))    {
                $u->nom = $request->nom;
            }
            if($request->has('prenom'))    {
                $u->prenom = $request->prenom;
            }
            if($request->has('email'))    {
                $u->email = $request->email;
            }
            // if($request->has('mot_de_passe'))    {
            //     $u->mot_de_passe = Hash::make($request->mot_de_passe);
            // }
            
            $u->save();

            return $commercial->load('utilisateur');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commercial $commercial)
    {
        $u = $commercial->utilisateur;
        $commercial->delete();
        $u->delete();

        return response()->json([
            'successCode' => 1,
            'message' => 'Suppression effectué avec succès',
        ]);
    }
}

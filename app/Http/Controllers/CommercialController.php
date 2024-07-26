<?php

namespace App\Http\Controllers;

use App\Models\Commercial;
use App\Http\Requests\StoreCommercialRequest;
use App\Http\Requests\UpdateCommercialRequest;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class CommercialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Commercial::with('utilisateur')->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommercialRequest $request)
    {
        // return $request;
        $u = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
        ]);
        
        $c = Commercial::create([
            'utilisateur_id' => $u->id,
        ]);

        return $c->load('utilisateur');

    }

    /**
     * Display the specified resource.
     */
    public function show(Commercial $commercial)
    {
        return $commercial->load('utilisateur')->prospections;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommercialRequest $request, Commercial $commercial)
    {
        $request->validate(
            [
                'email' => 'sometimes|required|string|email|max:255|unique:utilisateurs,email'. $commercial->utilisateur_id,
            ]
            );

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
            if($request->has('mot_de_passe'))    {
                $u->mot_de_passe = Hash::make($request->mot_de_passe);
            }
            
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
            'message' => 'Suppression effectué avec succès',
        ]);
    }
}

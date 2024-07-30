<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Http\Requests\StoreUtilisateurRequest;
use App\Http\Requests\UpdateUtilisateurRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('sanctum')->user();
        if($user && isset($user->responsable)) {
            return response()->json(Utilisateur::with('permissions')->get());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUtilisateurRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateur $utilisateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Utilisateur $utilisateur)
    {
        //
    }

    public function addPermission(Request $request, Utilisateur $utilisateur)
    {
        // Validate the request
        $request->validate([
            'permission_id' => 'required|array',
            'permission_id.*' => 'exists:permissions,id',
        ]);

        return $utilisateur;

        // Extract permission IDs from the validated request
        $permissionIds = $request->input('permission_id');

        // Attach permissions to the utilisateur
        $utilisateur->permissions()->attach($permissionIds);

        return response()->json(['successCode' => 1, 'message'=> 'Permissions ajoutées avec succès']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Utilisateur  $utilisateur
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUtilisateurRequest $request, Utilisateur $utilisateur)
    {
        return $request->validated();    

            if($request->has('nom'))    {
                $utilisateur->nom = $request->nom;
            }
            if($request->has('prenom'))    {
                $utilisateur->prenom = $request->prenom;
            }
            if($request->has('email'))    {
                $utilisateur->email = $request->email;
            }
            // if($request->has('mot_de_passe'))    {
            //     $u->mot_de_passe = Hash::make($request->mot_de_passe);
            // }

            $utilisateur->save();

            return $utilisateur;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateur $utilisateur)
    {
        //
    }
}

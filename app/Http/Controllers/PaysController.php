<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Http\Requests\StorePaysRequest;
use App\Http\Requests\UpdatePaysRequest;

class PaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return "ok";
        return response()->json(Pays::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaysRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pays $pays)
    {
        return $pays->communes;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaysRequest $request, Pays $pays)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pays $pays)
    {
        //
    }

    public function communesPays(Pays $pays)
    {
        return $pays->communes;
    }
}

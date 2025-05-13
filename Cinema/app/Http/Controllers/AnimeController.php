<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anime;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $user = Auth::user();
        $client = $user->client;  // Obtient le client associé à l'utilisateur

        $validated = $request->validate([
            'anime_id' => 'required|exists:animes,id',
            'adult' => 'required|integer|min:0',
            'etudiant' => 'required|integer|min:0',
            'enfant' => 'required|integer|min:0',
        ]);

        $animeId = $validated['anime_id'];

        // On vérifie si un anime est déjà dans le panier du client
        $existing = $client->animes()->where('anime_id', $animeId)->first();

        if ($existing) {
            // Mise à jour des quantités dans la table pivot
            $client->animes()->updateExistingPivot($animeId, [
                'adult' => $existing->pivot->adult + $validated['adult'],
                'etudiant' => $existing->pivot->etudiant + $validated['etudiant'],
                'enfant' => $existing->pivot->enfant + $validated['enfant'],
            ]);
        } else {
            // Ajouter un nouvel anime au panier du client
            $client->animes()->attach($animeId, [
                'adult' => $validated['adult'],
                'etudiant' => $validated['etudiant'],
                'enfant' => $validated['enfant'],
            ]);
        }

        return redirect()->route('movies.index')->with('success', '🎟️ Billets ajoutés au panier avec succès !');
    }



    /**
     * Display the specified resource.
     */
    public function show(Anime $anime)
    {
        return view('animes.show', compact('anime'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Movie;

class CartController extends Controller
{
    public function movies_client(){
        $user = Auth::user();
        $client = $user->client;
        $movies = $client->movies()->get(); 
        $animes = $client->animes()->get(); 
        return view('clients.cart', compact('movies', 'animes'));
    }
    
public function store(Request $request)
{
    $user = Auth::user();
    $client = $user->client;

    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,id',
        'adult' => 'required|integer|min:0',
        'etudiant' => 'required|integer|min:0',
        'enfant' => 'required|integer|min:0',
    ]);

    $movieId = $validated['movie_id'];

    // Vérifie si le film est déjà dans le panier
    $existing = $client->movies()->where('movie_id', $movieId)->first();

    if ($existing) {
        // Mise à jour des quantités dans la table pivot
        $client->movies()->updateExistingPivot($movieId, [
            'adult' => $existing->pivot->adult + $validated['adult'],
            'etudiant' => $existing->pivot->etudiant + $validated['etudiant'],
            'enfant' => $existing->pivot->enfant + $validated['enfant'],
        ]);
    } else {
        // Nouvelle ligne dans la pivot table
        $client->movies()->attach($movieId, [
            'adult' => $validated['adult'],
            'etudiant' => $validated['etudiant'],
            'enfant' => $validated['enfant'],
        ]);
    }

    return redirect()->route('movies.index')->with('success', '🎟️ Billets ajoutés au panier avec succès !');
}

    
    // Méthode pour supprimer un film du panier
    public function supprimer_panier(Cart $cart)
    {
        $cart->delete(); 

        // Redirection avec message de succès
        return redirect()->route('movies.index')->with('success', 'L\'article a été supprimé du panier !');
    }
}
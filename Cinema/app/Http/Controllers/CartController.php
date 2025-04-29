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
        $carts = $user->client->carts;  // relation client->carts
        return view('clients.cart', compact('carts'));
    }
    
    public function store(Request $request)
    {
        // Récupération de l'utilisateur et de son client
        $user = Auth::user();
        $client = $user->client;

        // Validation des entrées du formulaire
        $validated = $request->validate([
            'adult' => 'required|integer|min:0',
            'etudiant' => 'required|integer|min:0',
            'enfant' => 'required|integer|min:0',
            'movie_id' => 'required|exists:movies,id',
        ]);

        // On ajoute directement l'ID du client au tableau des données validées
        $validated['client_id'] = $client->id;

        // Vérification s'il existe déjà un panier pour ce client et ce film
        $existingCart = Cart::where('client_id', $validated['client_id'])
                            ->where('movie_id', $validated['movie_id'])
                            ->first();

        if ($existingCart) {
            // Si le panier existe, on met à jour les quantités
            $existingCart->update([
                'adult' => $existingCart->adult + $validated['adult'],
                'etudiant' => $existingCart->etudiant + $validated['etudiant'],
                'enfant' => $existingCart->enfant + $validated['enfant'],
            ]);
        } else {
            // Sinon, on crée un nouveau panier
            Cart::create($validated);
        }

        // Redirection avec message de succès
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
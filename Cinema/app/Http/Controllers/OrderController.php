<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index(){
        $user = Auth::user();
        $orders = Order::All();
        return view('orders.list', compact('orders'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
    
        if (!$user->client) {
            return back()->withErrors(['error' => 'Compte non associé à un client']);
        }
    
        $validated = $request->validate([
            'cart_id' => ['required', 'exists:carts,id'], // Un seul panier
            'total' => ['required', 'numeric', 'min:0']
        ]);
    
        DB::beginTransaction();
    
        try {
            // 1. Récupérer le panier avec ses films
            $cart = Cart::with('movies')
                      ->where('id', $validated['cart_id'])
                      ->where('client_id', $user->client->id)
                      ->firstOrFail();
    
            // 2. Calculer le total à partir des films du panier
            $prices = [
                'adult' => 10,
                'student' => 8,
                'child' => 5
            ];
    
            $calculatedTotal = 0;
            foreach ($cart->movies as $movie) {
                $calculatedTotal += ($movie->pivot->adult * $prices['adult'])
                                 + ($movie->pivot->etudiant * $prices['student'])
                                 + ($movie->pivot->enfant * $prices['child']);
            }
    
            // 3. Valider le total
            if (abs($calculatedTotal - $validated['total']) > 0.01) {
                throw new \Exception("Incohérence dans le calcul du total");
            }
    
            // 4. Créer la commande
            $order = Order::create([
                'client_id' => $user->client->id,
                'price' => $calculatedTotal,
                'status' => 'non payé'
            ]);
    
            // 5. Copier les films du panier vers la commande (via order_movie)
            foreach ($cart->movies as $movie) {
                $order->movies()->attach($movie->id, [
                    'adult' => $movie->pivot->adult,
                    'etudiant' => $movie->pivot->etudiant,
                    'enfant' => $movie->pivot->enfant
                ]);
            }
    
            // 6. Supprimer le panier (optionnel)
            $cart->delete();
    
            DB::commit();
    
            return redirect()->route('orders.show', $order)
                           ->with('success', 'Commande créée avec succès !');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur commande: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function createOrder(Cart $cart, float $total): Order
    {
        $order = new Order();
        $order->cart_id = $cart->id;
        $order->client_id = $cart->client_id;
        $order->price = $total;
        $order->status = 'non payé'; // Utilisez la valeur exacte de l'enum
        $order->photo = $cart->movie->image_url ?? null;
        
        $order->save();
        
        return $order;
    }

    private function calculateTotal(Cart $cart): float
    {
        $prices = config('prices', [
            'adult' => 10,
            'student' => 8,
            'child' => 5
        ]);

        return ($cart->adult * $prices['adult'])
            + ($cart->etudiant * $prices['student'])
            + ($cart->enfant * $prices['child']);
    }
    public function show(Order $order){
        return view('orders.show', compact('order'));
    }
}
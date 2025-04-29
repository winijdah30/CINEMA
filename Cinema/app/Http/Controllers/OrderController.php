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

    public function show(Order $order)
    {
        // Charger explicitement les relations
        $order->load('cart.movie');
        
        if (!$order->cart) {
            abort(404, 'Le panier associé à cette commande n\'existe pas');
        }

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        // 1. Validation des données avec messages personnalisés
        $user = Auth::user();
        $validated = $request->validate([
            'cart_ids' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($user) {  // Notez le use ($user)
                    // Vérification que l'utilisateur a un client associé
                    if (!$user->client) {
                        $fail("Votre compte utilisateur n'est pas associé à un client.");
                        return;
                    }
    
                    $validCarts = Cart::whereIn('id', $value)
                                    ->where('client_id', $user->client->id)
                                    ->count();
                    
                    if ($validCarts !== count($value)) {
                        $fail("Un ou plusieurs paniers ne vous appartiennent pas ou n'existent plus.");
                    }
                }
            ],
            'total' => 'required|numeric|min:0'
        ]);
    
        // 2. Constantes de prix (déclarées en haut de classe)
        $adultPrice = 10;
        $studentPrice = 8;
        $childPrice = 5;
    
        try {
            DB::beginTransaction();
    
            $orders = [];
            foreach ($validated['cart_ids'] as $cartId) {
                // 3. Vérification renforcée de propriété
                $cart = Cart::with('movie')
                          ->where('id', $cartId)
                          ->where('client_id', auth()->id())
                          ->firstOrFail();
                
                // 4. Calcul du prix avec vérification
                $calculatedTotal = ($cart->adult * $adultPrice) 
                                + ($cart->etudiant * $studentPrice) 
                                + ($cart->enfant * $childPrice);
                
                if (abs($calculatedTotal - $request->total) > 0.01) {
                    throw new \Exception("Incohérence de prix détectée");
                }
    
                // 5. Création de la commande
                $order = $this->createOrder($cart, $calculatedTotal);
                $orders[] = $order;
            }
    
            DB::commit();
    
            return redirect()->route('orders.index')
                           ->with('success', 'Commandes créées avec succès!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur commande: '.$e->getMessage());
            
            return back()->with('error', $this->getErrorMessage($e));
        }
    }
    
    // Méthode privée pour créer une commande
    private function createOrder(Cart $cart, float $price): Order
    {
        $qrContent = route('orders.show', ['order' => Str::uuid()]);
        
        $qrCode = QrCode::format('svg')
                       ->size(200)
                       ->generate($qrContent);
    
        $fileName = 'qrcodes/'.Str::uuid().'.svg';
        Storage::disk('public')->put($fileName, $qrCode);
    
        $order = Order::create([
            'photo' => $fileName,
            'price' => $price,
            'status' => 'non payé',
            'cart_id' => $cart->id,
            'qr_content' => $qrContent,
        ]);
    
        Mail::to(auth()->user()->email)
           ->queue(new OrderConfirmation($order));
    
        return $order;
    }
    
    // Gestion des messages d'erreur
    private function getErrorMessage(\Exception $e): string
    {
        return match (true) {
            $e instanceof ModelNotFoundException => 'Panier introuvable',
            str_contains($e->getMessage(), 'Incohérence') => 'Erreur de calcul du total',
            default => 'Erreur lors de la commande'
        };
    }
}
<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Order;
use App\Models\Anime;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        
        // Utilisez la relation comme query builder avec paginate()
        $orders = $client->orders()->paginate(10);
        
        return view('orders.list', compact('orders'));
    }
 // Assure-toi d'avoir installé cette bibliothèque

    public function store(Request $request)
    {
        $client = Auth::user()->client;

        // Définir les prix fixes
        $prixEtudiant = 8;
        $prixEnfant = 5;
        $prixAdulte = 10;

        // Calculer le prix total de la commande
        $totalPrice = 0;

        // Films dans le panier
        $movies = $client->movies()->get();
        foreach ($movies as $movie) {
            // Calculer le prix pour chaque catégorie et ajouter au prix total
            $totalPrice += $movie->pivot->adult * $prixAdulte;
            $totalPrice += $movie->pivot->etudiant * $prixEtudiant;
            $totalPrice += $movie->pivot->enfant * $prixEnfant;
        }

        // Animes dans le panier
        $animes = $client->animes()->get();
        foreach ($animes as $anime) {
            // Calculer le prix pour chaque catégorie et ajouter au prix total
            $totalPrice += $anime->pivot->adult * $prixAdulte;
            $totalPrice += $anime->pivot->etudiant * $prixEtudiant;
            $totalPrice += $anime->pivot->enfant * $prixEnfant;
        }

        // Créer une nouvelle commande
        $order = Order::create([
            'photo' => $this->generateQRCode($client->id), // Générer le QR Code pour la commande
            'price' => $totalPrice,
            'status' => 'non payé', // Statut initial
            'client_id' => $client->id,
        ]);

        // Associer les films et animes à la commande
        $order->movies()->attach($movies->pluck('id')->toArray(), [
            'adult' => $movies->sum('pivot.adult'),
            'etudiant' => $movies->sum('pivot.etudiant'),
            'enfant' => $movies->sum('pivot.enfant'),
        ]);
        
        $order->animes()->attach($animes->pluck('id')->toArray(), [
            'adult' => $animes->sum('pivot.adult'),
            'etudiant' => $animes->sum('pivot.etudiant'),
            'enfant' => $animes->sum('pivot.enfant'),
        ]);

        // Enlever les films et animes du panier de l'utilisateur
        $client->movies()->detach();
        $client->animes()->detach();

        // Rediriger vers la page des commandes avec un message de succès
        return redirect()->route('orders.index')->with('success', 'Votre commande a été créée avec succès !');
    }

    // Méthode pour générer le QR Code
    private function generateQRCode($clientId)
    {
        // Générer un QR Code avec l'ID de la commande pour le client
        $qrCodeData = route('orders.show', ['order' => $clientId]); // Ou n'importe quelle donnée qui permet d'identifier la commande
        $qrCode = QrCode::size(300)->generate($qrCodeData);

        // Enregistrer le QR Code comme une image
        $imagePath = public_path('images/qr_codes/')."order_{$clientId}.png";
        QrCode::size(300)->generate($qrCodeData, $imagePath);

        return "images/qr_codes/order_{$clientId}.png"; // Retourner le chemin de l'image
    }

    public function show($id)
    {
        // Récupérer la commande
        $order = Order::with(['movies', 'animes'])->findOrFail($id);

        // Retourner la vue et passer la commande
        return view('orders.show', compact('order'));
    }
     public function download($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        // Vérifier que l'utilisateur a le droit de télécharger ce billet
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Accès non autorisé à ce billet');
        }

        // Vérifier que le fichier existe
        if (!$order->photo || !Storage::exists($order->photo)) {
            abort(404, 'Le billet n\'est pas disponible');
        }

        // Déterminer le type MIME et l'extension
        $mime = Storage::mimeType($order->photo);
        $extension = pathinfo($order->photo, PATHINFO_EXTENSION);
        
        // Nom du fichier de téléchargement
        $filename = "billet-cinema-{$order->id}.{$extension}";

        // Headers pour forcer le téléchargement
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        // Retourner la réponse de téléchargement
        return new StreamedResponse(function() use ($order) {
            $stream = Storage::readStream($order->photo);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $headers);
    }
}
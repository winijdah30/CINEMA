@extends('template')

@section('title')
    🎟️ Détails de la commande
@endsection

@section('content')
<section class="min-h-screen bg-gray-100 py-10">
  <div class="container mx-auto max-w-5xl">
    @if(!$order->cart)
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200 text-center">
        <div class="text-red-500 text-5xl mb-4">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2">Commande invalide</h2>
        <p class="text-gray-600 mb-4">Le panier associé à cette commande n'existe plus.</p>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">Retour à mes commandes</a>
      </div>
    @else
      <!-- Le reste de votre vue existante -->
    
<section class="min-h-screen bg-gray-100 py-10">
  <div class="container mx-auto max-w-5xl">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails de votre commande</h1>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">← Retour à mes commandes</a>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Détails de la commande -->
        <div class="space-y-6">
          <div class="bg-blue-50 p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">📌 Informations de la commande</h2>
            <div class="space-y-3">
              <p><span class="font-medium">N° Commande:</span> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
              <p><span class="font-medium">Date:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
              <p><span class="font-medium">Statut:</span> 
                <span class="px-2 py-1 rounded-full text-xs 
                  {{ $order->status === 'payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                  {{ $order->status }}
                </span>
              </p>
            </div>
          </div>

          <div class="bg-gray-50 p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">🎬 Détails du film</h2>
            <div class="flex items-start gap-4">
              <img src="{{ $order->cart->movie->image_url ?? 'https://via.placeholder.com/150' }}" 
                   alt="{{ $order->cart->movie->name }}" class="w-24 h-32 object-cover rounded-lg">
              <div>
                <h3 class="font-bold text-lg">{{ $order->cart->movie->name }}</h3>
                <p class="text-gray-600">{{ $order->cart->movie->description ?? 'Aucune description disponible' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- QR Code et billets -->
        <div class="space-y-6">
          <div class="bg-green-50 p-6 rounded-xl flex flex-col items-center">
            <h2 class="text-xl font-semibold mb-4 text-center">🔒 Votre QR Code</h2>
            @if($order->photo)
              <img src="{{ asset('storage/'.$order->photo) }}" alt="QR Code" class="w-48 h-48 mb-4">
            @else
              <div class="bg-gray-200 w-48 h-48 flex items-center justify-center mb-4">
                <span class="text-gray-500">QR Code non disponible</span>
              </div>
            @endif
            <p class="text-sm text-gray-600 text-center">Présentez ce QR code à l'entrée de la salle</p>
          </div>

          <div class="bg-yellow-50 p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">🎟️ Vos billets</h2>
            <div class="space-y-3">
              @if($order->cart->adult > 0)
                <div class="flex justify-between">
                  <span>Adulte x{{ $order->cart->adult }}</span>
                  <span class="font-medium">{{ $order->cart->adult * 10 }} €</span>
                </div>
              @endif
              @if($order->cart->etudiant > 0)
                <div class="flex justify-between">
                  <span>Étudiant x{{ $order->cart->etudiant }}</span>
                  <span class="font-medium">{{ $order->cart->etudiant * 8 }} €</span>
                </div>
              @endif
              @if($order->cart->enfant > 0)
                <div class="flex justify-between">
                  <span>Enfant x{{ $order->cart->enfant }}</span>
                  <span class="font-medium">{{ $order->cart->enfant * 5 }} €</span>
                </div>
              @endif
              <div class="border-t pt-2 mt-2 font-bold flex justify-between">
                <span>Total</span>
                <span>{{ $order->price }} €</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Boutons d'action -->
      <div class="mt-8 flex justify-end gap-4">
        <a href="{{ route('orders.download', $order->id) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all">
          <i class="fas fa-download mr-2"></i>Télécharger le QR Code
        </a>
        @if($order->status !== 'payé')
          <a href="{{ route('orders.pay', $order->id) }}" 
             class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all">
            <i class="fas fa-credit-card mr-2"></i>Payer la commande
          </a>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
@endsection
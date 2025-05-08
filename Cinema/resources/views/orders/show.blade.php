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
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Détails de votre commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
          <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">← Retour à mes commandes</a>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          <!-- Détails de la commande -->
          <div class="space-y-6">
            <div class="bg-blue-50 p-6 rounded-xl">
              <h2 class="text-xl font-semibold mb-4">📌 Informations</h2>
              <div class="space-y-3">
                <p><span class="font-medium">Date:</span> 
                  {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
                <p><span class="font-medium">Statut:</span> 
                  <span class="px-2 py-1 rounded-full text-xs 
                    {{ $order->status === 'payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </p>
              </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl">
              <h2 class="text-xl font-semibold mb-4">🎬 Films réservés</h2>
              <div class="space-y-6">
                @foreach($order->cart->movies as $movie)
                <div class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0">
                  <img src="{{ $movie->image_url ?? asset('images/default-movie.jpg') }}" 
                       alt="{{ $movie->name }}" 
                       class="w-20 h-28 object-cover rounded-lg">
                  <div class="flex-1">
                    <h3 class="font-bold text-lg">{{ $movie->name }}</h3>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                      <p><i class="fas fa-clock mr-1 text-gray-400"></i> {{ $movie->duration }} min</p>
                      <p><i class="fas fa-calendar-alt mr-1 text-gray-400"></i> 
                        {{ $movie->date->format('d/m/Y') }}
                      </p>
                      <p><i class="fas fa-film mr-1 text-gray-400"></i> {{ $movie->version }}</p>
                      <p><i class="fas fa-door-open mr-1 text-gray-400"></i> Salle {{ $movie->salle_id }}</p>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Détails de paiement -->
          <div class="space-y-6">
            <!-- QR Code -->
            @if($order->status === 'payé')
            <div class="bg-green-50 p-6 rounded-xl">
              <h2 class="text-xl font-semibold mb-4 text-center">✅ Paiement effectué</h2>
              <div class="flex flex-col items-center">
                @if($order->qr_code_path)
                  <img src="{{ asset('storage/'.$order->qr_code_path) }}" 
                       alt="Billet électronique" 
                       class="w-48 h-48 mb-4">
                  <a href="{{ route('orders.download', $order->id) }}" 
                     class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-download mr-1"></i>Télécharger mon billet
                  </a>
                @else
                  <div class="bg-gray-200 w-48 h-48 flex items-center justify-center mb-4 rounded-lg">
                    <span class="text-gray-500">Billet en génération</span>
                  </div>
                @endif
              </div>
            </div>
            @endif

            <!-- Récapitulatif -->
            <div class="bg-yellow-50 p-6 rounded-xl">
              <h2 class="text-xl font-semibold mb-4">💳 Détails de paiement</h2>
              <div class="space-y-4">
                <div class="space-y-3">
                  @foreach($order->cart->movies as $movie)
                  <div class="bg-white p-3 rounded-lg">
                    <h4 class="font-medium">{{ $movie->name }}</h4>
                    @if($order->cart->adult > 0)
                      <div class="flex justify-between text-sm mt-1">
                        <span>Adulte ×{{ $order->cart->adult }}</span>
                        <span>{{ $order->cart->adult * 10 }} €</span>
                      </div>
                    @endif
                    @if($order->cart->etudiant > 0)
                      <div class="flex justify-between text-sm mt-1">
                        <span>Étudiant ×{{ $order->cart->etudiant }}</span>
                        <span>{{ $order->cart->etudiant * 8 }} €</span>
                      </div>
                    @endif
                    @if($order->cart->enfant > 0)
                      <div class="flex justify-between text-sm mt-1">
                        <span>Enfant ×{{ $order->cart->enfant }}</span>
                        <span>{{ $order->cart->enfant * 5 }} €</span>
                      </div>
                    @endif
                  </div>
                  @endforeach
                </div>

                <div class="border-t border-gray-200 pt-3">
                  <div class="flex justify-between font-bold">
                    <span>Total TTC</span>
                    <span>{{ number_format($order->price, 2, ',', ' ') }} €</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Instructions -->
        @if($order->status === 'payé')
        <div class="mt-8 bg-purple-50 p-6 rounded-xl">
          <h2 class="text-xl font-semibold mb-3">📲 Comment utiliser votre billet ?</h2>
          <ol class="list-decimal list-inside space-y-2">
            <li>Téléchargez et sauvegardez votre QR Code</li>
            <li>Présentez-le à l'entrée de la salle</li>
            <li>Un contrôleur scannera votre code</li>
          </ol>
        </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endsection
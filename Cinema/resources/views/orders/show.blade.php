@extends('template')

@section('title')
    🎟️ Détails de la commande
@endsection

@section('content')
<section class="min-h-screen bg-gray-100 py-10">
  <div class="container mx-auto max-w-5xl">
    @if(!$order->movies->count())
      <!-- Cas où la commande est invalide -->
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200 text-center transform transition-all hover:scale-[1.01] duration-300">
        <div class="text-red-500 text-5xl mb-4">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2">Commande invalide</h2>
        <p class="text-gray-600 mb-4">Le panier associé à cette commande n'existe plus.</p>
        <a href="{{ route('orders.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
          <i class="fas fa-arrow-left mr-2"></i>Retour à mes commandes
        </a>
      </div>
    @else
      <!-- Cas où la commande est valide -->
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200 transform transition-all hover:shadow-2xl duration-300">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
              <span class="bg-blue-100 text-blue-800 p-2 rounded-full">
                <i class="fas fa-ticket-alt"></i>
              </span>
              Commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-gray-500 mt-1">Merci pour votre réservation !</p>
          </div>
          <a href="{{ route('orders.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Retour à mes commandes
          </a>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          <!-- Colonne gauche - Détails -->
          <div class="space-y-6">
            <!-- Bloc Informations -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
              <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <span class="bg-blue-600 text-white p-2 rounded-full">
                  <i class="fas fa-info-circle"></i>
                </span>
                Informations de commande
              </h2>
              <div class="space-y-3 text-gray-700">
                <p class="flex items-center gap-2">
                  <i class="fas fa-calendar-day text-blue-500 w-5"></i>
                  <span class="font-medium">Date :</span> 
                  {{ $order->created_at->format('d/m/Y à H:i') }}
                </p>
                <p class="flex items-center gap-2">
                  <i class="fas fa-tag text-blue-500 w-5"></i>
                  <span class="font-medium">Statut :</span> 
                  <span class="px-3 py-1 rounded-full text-sm font-medium
                    {{ $order->status === 'payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </p>
                <p class="flex items-center gap-2">
                  <i class="fas fa-hashtag text-blue-500 w-5"></i>
                  <span class="font-medium">Référence :</span> 
                  CIN-{{ $order->id }}-{{ strtoupper(substr(md5($order->id), 0, 4)) }}
                </p>
              </div>
            </div>

            <!-- Bloc Films -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
              <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <span class="bg-gray-800 text-white p-2 rounded-full">
                  <i class="fas fa-film"></i>
                </span>
                Votre sélection de films
              </h2>
              <div class="space-y-6">
                @foreach($order->movies as $movie)
                <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 group">
                  <div class="relative">
                    <img src="{{ $movie->image_url ?? asset('images/default-movie.jpg') }}" 
                         alt="{{ $movie->name }}" 
                         class="w-20 h-28 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow duration-300">
                    <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">
                      +{{ $movie->pivot->adult + $movie->pivot->etudiant + $movie->pivot->enfant }} place(s)
                    </div>
                  </div>
                  <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition-colors duration-200">{{ $movie->name }}</h3>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                      <p class="flex items-center"><i class="fas fa-clock mr-2 text-gray-400 w-4"></i> {{ $movie->duration }} min</p>
                      <p class="flex items-center"><i class="fas fa-calendar-alt mr-2 text-gray-400 w-4"></i> 
                        {{ $movie->date->format('d/m/Y') }}
                      </p>
                      <p class="flex items-center"><i class="fas fa-film mr-2 text-gray-400 w-4"></i> {{ $movie->version }}</p>
                      <p class="flex items-center"><i class="fas fa-door-open mr-2 text-gray-400 w-4"></i> Salle {{ $movie->salle_id }}</p>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Colonne droite - Paiement -->
          <div class="space-y-6">
            <!-- QR Code -->
            @if($order->status === 'payé')
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 text-center">
              <h2 class="text-xl font-semibold mb-4 flex items-center justify-center gap-2">
                <span class="bg-green-600 text-white p-2 rounded-full">
                  <i class="fas fa-check-circle"></i>
                </span>
                Paiement confirmé
              </h2>
              <div class="flex flex-col items-center">
                @if($order->photo)
                  <div class="mb-4 p-4 bg-white rounded-lg shadow-inner border-2 border-dashed border-green-300">
                    <img src="{{ asset('storage/'.$order->photo) }}" 
                         alt="Billet électronique" 
                         class="w-48 h-48 object-contain">
                  </div>
                  <a href="{{ route('orders.download', $order->id) }}" 
                     class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-download mr-2"></i>Télécharger mon billet
                  </a>
                  <p class="text-xs text-gray-500 mt-2">Valable jusqu'au {{ $order->created_at->addDays(30)->format('d/m/Y') }}</p>
                @else
                  <div class="bg-gray-200 w-48 h-48 flex items-center justify-center mb-4 rounded-lg animate-pulse">
                    <span class="text-gray-500">Génération en cours...</span>
                  </div>
                @endif
              </div>
            </div>
            @endif

            <!-- Récapitulatif -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-100 p-6 rounded-xl border border-amber-200">
              <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <span class="bg-amber-600 text-white p-2 rounded-full">
                  <i class="fas fa-receipt"></i>
                </span>
                Récapitulatif de paiement
              </h2>
              <div class="space-y-4">
                <div class="space-y-3">
                  @foreach($order->movies as $movie)
                  <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="font-medium text-gray-800">{{ $movie->name }}</h4>
                    @if($movie->pivot->adult > 0)
                      <div class="flex justify-between text-sm mt-2 px-2">
                        <span class="text-gray-600">Adulte ×{{ $movie->pivot->adult }}</span>
                        <span class="font-medium">{{ $movie->pivot->adult * 10 }} €</span>
                      </div>
                    @endif
                    @if($movie->pivot->etudiant > 0)
                      <div class="flex justify-between text-sm mt-2 px-2">
                        <span class="text-gray-600">Étudiant ×{{ $movie->pivot->etudiant }}</span>
                        <span class="font-medium">{{ $movie->pivot->etudiant * 8 }} €</span>
                      </div>
                    @endif
                    @if($movie->pivot->enfant > 0)
                      <div class="flex justify-between text-sm mt-2 px-2">
                        <span class="text-gray-600">Enfant ×{{ $movie->pivot->enfant }}</span>
                        <span class="font-medium">{{ $movie->pivot->enfant * 5 }} €</span>
                      </div>
                    @endif
                  </div>
                  @endforeach
                </div>

                <div class="border-t border-amber-200 pt-4 mt-2">
                  <div class="flex justify-between font-bold text-lg text-amber-900">
                    <span>Total TTC</span>
                    <span>{{ number_format($order->price, 2, ',', ' ') }} €</span>
                  </div>
                  <p class="text-xs text-amber-700 mt-1 text-right">TVA incluse</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Instructions -->
        @if($order->status === 'payé')
        <div class="mt-8 bg-gradient-to-br from-purple-50 to-indigo-100 p-6 rounded-xl border border-purple-200">
          <h2 class="text-xl font-semibold mb-3 flex items-center gap-2">
            <span class="bg-purple-600 text-white p-2 rounded-full">
              <i class="fas fa-mobile-alt"></i>
            </span>
            Comment utiliser votre billet ?
          </h2>
          <ol class="list-decimal list-inside space-y-3 text-gray-700">
            <li class="font-medium">Téléchargez et sauvegardez votre QR Code sur votre appareil</li>
            <li>Présentez-le à l'entrée de la salle <span class="text-sm text-gray-500">(sur écran ou imprimé)</span></li>
            <li>Un contrôleur scannera votre code pour valider votre entrée</li>
            <li>Profitez du film ! <i class="fas fa-smile-beam text-yellow-500 ml-1"></i></li>
          </ol>
          <div class="mt-4 p-3 bg-white rounded-lg border border-purple-100 text-sm text-gray-600">
            <i class="fas fa-info-circle text-purple-500 mr-2"></i>
            Pensez à arriver 15 minutes avant le début de la séance.
          </div>
        </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endsection
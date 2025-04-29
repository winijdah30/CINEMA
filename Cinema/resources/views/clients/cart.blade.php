@extends('template')

@section('title') 🎟️ Mon Panier @endsection

@section('content')
<section class="min-h-screen bg-gray-100 py-10">
  <div class="container mx-auto max-w-5xl">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
      <div class="flex justify-between items-center mb-6">
        <a href="{{ route('movies.index') }}" class="text-blue-600 hover:underline flex items-center">
          <i class="fas fa-arrow-left mr-2"></i> Continuer mes achats
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Mon Panier</h1>
      </div>

      @if (auth()->user()->client->carts->isEmpty())
        <div class="text-center py-10">
          <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg">Votre panier est vide.</p>
          <a href="{{ route('movies.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all">
            Voir les films
          </a>
        </div>
      @else
        <div class="space-y-4">
          @php
            $total = 0;
            $adultPrice = 10;
            $studentPrice = 8;
            $childPrice = 5;
          @endphp

          @foreach(auth()->user()->client->carts as $cart)
            @php
              $movie = $cart->movie;
              $subtotal = ($cart->adult * $adultPrice) 
                        + ($cart->etudiant * $studentPrice) 
                        + ($cart->enfant * $childPrice);
              $total += $subtotal;
            @endphp

            <div class="flex flex-col md:flex-row justify-between items-center bg-blue-50 p-4 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
              <div class="flex items-center gap-4 w-full md:w-1/2">
                <img src="{{ $movie->image_url ?? asset('images/default-movie.jpg') }}" 
                     alt="{{ $movie->name }}" 
                     class="w-20 h-20 object-cover rounded-lg shadow-sm">
                <div>
                  <h4 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-film text-blue-500 mr-2"></i>{{ $movie->name }}
                  </h4>
                  <div class="mt-2">
                    @if ($cart->adult > 0)
                      <span class="inline-block bg-white px-2 py-1 rounded-full text-xs font-medium mr-2">
                        <i class="fas fa-user text-gray-700 mr-1"></i> Adulte × {{ $cart->adult }}
                      </span>
                    @endif
                    @if ($cart->etudiant > 0)
                      <span class="inline-block bg-white px-2 py-1 rounded-full text-xs font-medium mr-2">
                        <i class="fas fa-user-graduate text-blue-500 mr-1"></i> Étudiant × {{ $cart->etudiant }}
                      </span>
                    @endif
                    @if ($cart->enfant > 0)
                      <span class="inline-block bg-white px-2 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-child text-yellow-500 mr-1"></i> Enfant × {{ $cart->enfant }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-end w-full md:w-1/2 gap-6 mt-4 md:mt-0">
                <div class="text-center bg-white px-4 py-2 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Sous-total</p>
                  <p class="font-medium text-blue-600">{{ number_format($subtotal, 2, ',', ' ') }} €</p>
                </div>
                <form action="{{ route('carts.destroy', $cart) }}" method="POST" 
                      onsubmit="return confirm('Supprimer cet article de votre panier ?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                    <i class="fas fa-trash-alt"></i>
                    <span class="sr-only">Supprimer</span>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-10 border-t border-gray-200 pt-6">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-xl font-semibold text-gray-800">Total</h3>
              <p class="text-sm text-gray-500">TVA incluse</p>
            </div>
            <span class="text-2xl font-bold text-blue-600">{{ number_format($total, 2, ',', ' ') }} €</span>
          </div>
          
          <form method="POST" action="{{ route('orders.store') }}" class="mt-6">
            @csrf
            @foreach(auth()->user()->client->carts as $cart)
              <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
            @endforeach
            <input type="hidden" name="total" value="{{ $total }}">
            
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-all flex items-center justify-center gap-2">
              <i class="fas fa-check-circle"></i>
              Valider la commande
            </button>
          </form>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- Modal de confirmation -->
@if(session('order_success'))
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl transform transition-all">
      <div class="text-center">
        <div class="text-green-500 text-5xl mb-4 animate-bounce">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Commande validée !</h3>
        <p class="text-gray-600 mb-4">
          Un email de confirmation a été envoyé à <span class="font-medium">{{ auth()->user()->email }}</span>.
        </p>
        <div class="flex justify-center gap-4">
          <a href="{{ route('orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all">
            Mes commandes
          </a>
          <button @click="show = false" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all">
            Fermer
          </button>
        </div>
      </div>
    </div>
  </div>
@endif

@endsection
@extends('template')

@section('title')
    🎬 {{ $movie->name }} - Détails du film
@endsection

@section('navbar')
    <a href="{{ route('home') }}" class="flex items-center">
        <img src="{{ asset('images/home.jpg') }}" 
             alt="Logo Nord Pitcha" 
             class="h-16">
    </a>
    <ul class="flex space-x-4">
        <li><a class="nav-link fw-bold" href="{{route('clients.cart')}}">
            <i class="bi bi-cart"></i> Panier</a></li>  
        <li><a href="{{route('orders.index')}}" class="text-gray-300 hover:text-white">Commandes</a></li>
        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
        <li>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="nav-link btn btn-danger px-3 rounded border-0">
            Déconnexion
            </button>
        </form>
        </li>
    </ul>
@endsection

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Film Header -->
        <div class="p-8 bg-gradient-to-r from-blue-900 to-purple-900 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $movie->name }}</h1>
                    <div class="flex items-center space-x-4">
                        @php 
                            $hours = intdiv($movie->duration, 60);
                            $minutes = $movie->duration % 60;
                        @endphp
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $hours }}h{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-block bg-yellow-400 bg-opacity-90 text-yellow-900 px-3 py-1 rounded-full text-sm font-semibold">
                        En salle maintenant
                    </span>
                </div>
            </div>
        </div>

        <!-- Film Content -->
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Synopsis
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $movie->description }}</p>
            </div>

            <!-- Ticket Selection Form -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Réserver vos places
                    </h2>

                    <div class="space-y-5">
                        @foreach ([
                            'adult' => ['Adulte', '18+', 'text-blue-600', 'bg-blue-50'],
                            'etudiant' => ['Étudiant', 'Sur présentation', 'text-green-600', 'bg-green-50'],
                            'enfant' => ['Enfant', '-12 ans', 'text-purple-600', 'bg-purple-50']
                        ] as $key => [$label, $sublabel, $textColor, $bgColor])
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 rounded-lg {{ $bgColor }}">
                            <div class="mb-3 sm:mb-0">
                                <h3 class="font-semibold text-lg {{ $textColor }}">{{ $label }}</h3>
                                <p class="text-sm text-gray-600">{{ $sublabel }}</p>
                            </div>
                            <div class="flex items-center">
                                <button type="button" onclick="updateCount('{{ $key }}', -1)"
                                    class="w-8 h-8 rounded-full bg-white border {{ $textColor }} border-current flex items-center justify-center hover:bg-opacity-20 hover:bg-current transition-all">
                                    −
                                </button>
                                <input type="number" name="{{ $key }}" id="{{ $key }}" value="0" min="0"
                                    class="mx-3 w-14 text-center border-0 bg-transparent font-bold text-lg focus:ring-0 {{ $textColor }}" readonly>
                                <button type="button" onclick="updateCount('{{ $key }}', 1)"
                                    class="w-8 h-8 rounded-full bg-white border {{ $textColor }} border-current flex items-center justify-center hover:bg-opacity-20 hover:bg-current transition-all">
                                    +
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">

                    <button type="submit"
                        class="w-full mt-8 py-3 px-6 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:from-indigo-700 hover:to-blue-700 transition-all transform hover:scale-[1.01] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Ajouter au panier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCount(id, delta) {
        const input = document.getElementById(id);
        let value = parseInt(input.value);
        value = Math.max(0, value + delta);
        input.value = value;
    }
</script>
@endsection
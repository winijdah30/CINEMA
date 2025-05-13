@extends('template')

@section('title', '🎌 ' . $anime->name . ' - Détails')
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
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-pink-500 to-purple-600 rounded-2xl overflow-hidden shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="relative z-10 p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $anime->name }}</h1>
            <div class="flex items-center space-x-4 text-white">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @php 
                        $hours = intdiv($anime->duration, 60);
                        $minutes = $anime->duration % 60;
                    @endphp
                    {{ $hours }}h{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                </span>
                <span class="flex items-center bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                    Anime
                </span>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Description -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Synopsis
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $anime->description }}</p>
            </div>
        </div>

        <!-- Right Column - Ticket Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('animes.store') }}">
                    @csrf
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Réservation
                    </h2>

                    <div class="space-y-4">
                        @foreach ([
                            'adult' => ['Adulte', 'text-blue-500', 'bg-blue-50'],
                            'etudiant' => ['Étudiant', 'text-green-500', 'bg-green-50'],
                            'enfant' => ['Enfant', 'text-pink-500', 'bg-pink-50']
                        ] as $key => [$label, $textColor, $bgColor])
                        <div class="p-4 rounded-lg {{ $bgColor }} border border-transparent hover:border-{{ substr($textColor, 5) }} transition-all">
                            <div class="flex justify-between items-center">
                                <span class="font-medium {{ $textColor }}">{{ $label }}</span>
                                <div class="flex items-center">
                                    <button type="button" onclick="updateCount('{{ $key }}', -1)"
                                        class="w-8 h-8 rounded-full bg-white border {{ $textColor }} border-current flex items-center justify-center hover:bg-opacity-20 hover:bg-current transition-all">
                                        −
                                    </button>
                                    <input type="number" name="{{ $key }}" id="{{ $key }}" value="0" min="0"
                                        class="mx-2 w-12 text-center border-0 bg-transparent font-bold {{ $textColor }} focus:ring-0" readonly>
                                    <button type="button" onclick="updateCount('{{ $key }}', 1)"
                                        class="w-8 h-8 rounded-full bg-white border {{ $textColor }} border-current flex items-center justify-center hover:bg-opacity-20 hover:bg-current transition-all">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="anime_id" value="{{ $anime->id }}">

                    <button type="submit"
                        class="w-full mt-6 py-3 px-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg shadow-md hover:from-purple-600 hover:to-pink-600 transition-all transform hover:scale-[1.01] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirmer la réservation
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
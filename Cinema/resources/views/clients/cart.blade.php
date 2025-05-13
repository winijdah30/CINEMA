@extends('template')

@section('title', '🎟️ Mon Panier')

@section('navbar')
    <a href="{{ route('home') }}" class="flex items-center">
        <img src="{{ asset('images/home.jpg') }}" 
             alt="Logo Nord Pitcha" 
             class="h-16">
    </a>
    <ul class="flex space-x-4">
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
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
        <div class="flex items-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h2 class="text-3xl font-bold text-gray-800">Votre panier</h2>
        </div>

        @php
            $client = Auth::user()->client;
            // Récupérer les films du client
            $movies = $client->movies()->get();
            // Récupérer les animes du client
            $animes = $client->animes()->get();
        @endphp

        @if($movies->isEmpty() && $animes->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Votre panier est vide</h3>
                <p class="text-gray-500">Parcourez notre catalogue et ajoutez des films ou animes à votre panier</p>
                <a href="{{ route('movies.index') }}" class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    Explorer les films
                </a>
                <a href="{{ route('animes.index') }}" class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    Explorer les animes
                </a>
            </div>
        @else
            <!-- Films -->
            @if($movies->isNotEmpty())
                <div class="overflow-x-auto">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Films</h3>
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left font-semibold">Titre</th>
                                <th class="py-3 px-6 text-center font-semibold">Adulte</th>
                                <th class="py-3 px-6 text-center font-semibold">Étudiant</th>
                                <th class="py-3 px-6 text-center font-semibold">Enfant</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($movies as $movie)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ $movie->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-sm">
                                            {{ $movie->pivot->adult }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-sm">
                                            {{ $movie->pivot->etudiant }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-purple-100 text-purple-800 py-1 px-3 rounded-full text-sm">
                                            {{ $movie->pivot->enfant }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr class="my-8 border-gray-300">
            @endif

            <!-- Animes -->
            @if($animes->isNotEmpty())
                <div class="overflow-x-auto">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Animes</h3>
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left font-semibold">Titre</th>
                                <th class="py-3 px-6 text-center font-semibold">Adulte</th>
                                <th class="py-3 px-6 text-center font-semibold">Étudiant</th>
                                <th class="py-3 px-6 text-center font-semibold">Enfant</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($animes as $anime)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12m4 0h2m0 14h-2M3 19h12m2 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0H7m-4 0H1a2 2 0 00-2 2v14a2 2 0 002 2h2" />
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ $anime->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-sm">
                                            {{ $anime->pivot->adult }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-sm">
                                            {{ $anime->pivot->etudiant }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-purple-100 text-purple-800 py-1 px-3 rounded-full text-sm">
                                            {{ $anime->pivot->enfant }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-8 flex justify-end">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-md hover:from-indigo-700 hover:to-purple-700 transition duration-200 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Valider la réservation
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
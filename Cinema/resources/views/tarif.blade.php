@extends('template')
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
    <div class="container mx-auto mt-8 p-6 bg-gray-800 rounded-lg shadow-lg">
        <h1 class="text-4xl font-bold text-center mb-6">Nos Tarifs</h1>

        <!-- Tarifs Films -->
        <div class="mb-8">
            <h3 class="text-2xl font-semibold text-blue-400 mb-4">Tarifs Films</h3>
            <ul class="space-y-2">
                <li class="text-lg">🎥 Adulte : <span class="font-bold text-green-400">10€</span></li>
                <li class="text-lg">🎓 Etudiant : <span class="font-bold text-green-400">8€</span></li>
                <li class="text-lg">👶 Enfant : <span class="font-bold text-green-400">5€</span></li>
                <li class="text-lg">👨‍👩‍👧‍👦 Groupe (7 ou plus) : <span class="font-bold text-green-400">8€/personne</span></li>
            </ul>
        </div>

        <!-- Tarifs Animes -->
        <div>
            <h3 class="text-2xl font-semibold text-blue-400 mb-4">Tarifs Animes</h3>
            <ul class="space-y-2">
                <li class="text-lg">🍿 Adulte : <span class="font-bold text-yellow-400">4€</span></li>
                <li class="text-lg">🎓 Etudiant : <span class="font-bold text-yellow-400">3€</span></li>
                <li class="text-lg">👶 Enfant : <span class="font-bold text-yellow-400">2€</span></li>
            </ul>
        </div>

    </div>
@endsection
@extends('template')

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
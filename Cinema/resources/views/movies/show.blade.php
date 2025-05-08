@extends('template')

@section('title')
    Information sur le film : {{ $movie->name }}
@endsection

@section('content')
<h4><strong>Titre : </strong><i>{{$movie->name}}</i></h4>
<h6><strong>description : </strong><p>{{$movie->description}}</p></h6>
@php 
    $d1 = 0;
    $d2 = $movie->duration;
    while ($d2 > 60) {
        $d1++;
        $d2 = $d2 - 60;
    }
@endphp
<h5>Durée : {{ $d1 }}h{{ str_pad($d2, 2, '0', STR_PAD_LEFT) }}</h5>
<form method="POST" action="{{ route('cart.store') }}" class="max-w-lg mx-auto mt-8 p-6 bg-white rounded-2xl shadow-xl">
    @csrf
    <h2 class="text-2xl font-bold mb-6 text-center">Choisir vos billets</h2>

    @foreach (['adult' => 'Adulte', 'etudiant' => 'Étudiant', 'enfant' => 'Enfant'] as $key => $label)
        <div class="flex items-center justify-between mb-4">
            <span class="text-lg font-medium w-24">{{ $label }}</span>
            <div class="flex items-center gap-3">
                <button type="button"
                    onclick="updateCount('{{ $key }}', -1)"
                    class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600">
                    −
                </button>
                <input type="number" name="{{ $key }}" id="{{ $key }}" value="0" min="0"
                    class="w-14 text-center border rounded-md py-1 px-2" readonly>
                <button type="button"
                    onclick="updateCount('{{ $key }}', 1)"
                    class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-green-600">
                    +
                </button>
            </div>
        </div>
    @endforeach

    <input type="hidden" name="movie_id" value="{{ $movie->id }}">

    <button type="submit"
        class="w-full mt-6 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-all">
        Valider
    </button>
</form>

<script>
    function updateCount(id, delta) {
        const input = document.getElementById(id);
        let value = parseInt(input.value);
        value = Math.max(0, value + delta);
        input.value = value;
    }
</script>

@endsection
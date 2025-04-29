@extends('template')

@section('title') Modification d'un film @endsection
@section('content')
    <x-movie_form :action="route('movies.update', $movie->id)" :cancelRoute="route('movies.index')" :movie="$movie" :categories="$categories" />
@endsection
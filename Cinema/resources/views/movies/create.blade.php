@extends('template')

@section('title') Création d'un film @endsection
@section('content')
    <x-movie_form :action="route('movies.store')" :cancelRoute="route('movies.index')" :categories="$categories" />
@endsection
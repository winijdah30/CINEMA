<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Anime;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::orderBy('name')->get();
        $animes = Anime::orderBy('name')->get();
       return view('movies.list', compact('movies','animes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('movies.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return view('movies.show',compact('movie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        $categories = Category::all();
        return view('movies.edit', compact('movie', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        // if (Gate::denies('delete', $movie)) {
        //     return redirect()->route('movies.index')->with('error', 'Vous pouvez pas supprimez !!');
        // }else{  
        //     $movie->categories()->detach();
        //     $movie->delete();
        //     return redirect()->route('movies.index');
        // }
        $movie->categories()->detach();
        $movie->delete();
        return redirect()->route('movies.index');
    }
}

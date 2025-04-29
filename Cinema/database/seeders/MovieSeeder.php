<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movie::factory()->count(10)->create()->each(function ($movie) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
            $movie->categories()->sync($categories);
        });

        foreach (Movie::all() as $movie) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
            $movie->categories()->sync($categories); 
        }
    }
}

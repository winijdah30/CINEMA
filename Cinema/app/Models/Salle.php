<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'place'
    ];

    public function movies(){
        return $this->hasMany(Movie::class);
    }
    public function animes(){
        return $this->hasMany(Movie::class);
    }
}

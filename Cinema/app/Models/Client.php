<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Movie;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Anime;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mail',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'client_movie')
                    ->withPivot('adult', 'etudiant', 'enfant');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'client_anime')
                    ->withPivot('adult', 'etudiant', 'enfant');
    }
}

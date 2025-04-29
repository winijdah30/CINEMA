<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Models\Movie;
use App\Models\Order;


class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'adult',
        'etudiant',
        'enfant',
        'client_id',
        'movie_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
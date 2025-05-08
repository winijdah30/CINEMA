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
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function movies()
    {
        return $this->belongsToMany(Movie::class)
                    ->withPivot('adult', 'etudiant', 'enfant')
                    ->withTimestamps();
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
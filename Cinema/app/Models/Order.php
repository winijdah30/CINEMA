<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\Client;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'price',
        'status',
        'client_id'
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function movies()
    {
        return $this->belongsToMany(Movie::class)->withPivot('adult', 'etudiant', 'enfant');
    }

    public function animes()
    {
        return $this->belongsToMany(Anime::class)->withPivot('adult', 'etudiant', 'enfant');
    }
}

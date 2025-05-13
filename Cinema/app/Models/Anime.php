<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salle;
use App\Models\Anime;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anime extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'duration',
        'date',
        'salle_id',
    ];
    protected $casts = [
        'date' => 'datetime', // Pour que la colonne 'date' soit une instance Carbon
    ];
    public function salle(){
        return $this->belongsTo(Salle::class);
    }

    public function clients(){
        return $this->belongsToMany(Client::class, 'client_anime')
                    ->withPivot('adult', 'etudiant', 'enfant');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'anime_order')
                    ->withPivot('adult', 'etudiant', 'enfant'); 
    }
}

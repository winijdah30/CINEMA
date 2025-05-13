<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salle;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'duration',
        'date',
        'version',
        'salle_id',
    ];
    protected $casts = [
        'date' => 'datetime', // Pour que la colonne 'date' soit une instance Carbon
    ];
    public function categories(){
        return $this->BelongsToMany(Category::class);
    }
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'movie_order')
                    ->withPivot('adult', 'etudiant', 'enfant'); 
    }

}
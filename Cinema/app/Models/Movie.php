<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salle;
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
    
    public function categories(){
        return $this->BelongsToMany(Category::class);
    }
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
}
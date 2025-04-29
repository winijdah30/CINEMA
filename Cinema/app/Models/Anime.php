<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salle;
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
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
}

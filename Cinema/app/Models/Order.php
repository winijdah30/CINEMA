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
        'cart_id',
        'client_id'
    ];
    public function cart(){
        return $this->belongsTo(Cart::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
}

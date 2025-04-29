<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', [ 'admin']);
        })->limit(5)->get();
    
        foreach ($users as $user) {
            $user->assignRole('client'); // Assigner le rôle client
    
            // Créer un client et lui attribuer un user_id
            $client = Client::factory()->create([
                'user_id' => $user->id,
            ]);

            // Récupérer 3 items aléatoires et les attacher au client
            // $items = Item::inRandomOrder()->limit(3)->get();
            // foreach ($items as $item) {
            //     $client->items()->attach($item->id);
            // }
        }
    }
}

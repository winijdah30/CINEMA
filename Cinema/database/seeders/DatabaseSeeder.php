<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\MovieSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SalleSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\AnimeSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]); 
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        
        $this->call(CategorySeeder::class);
        $this->call(SalleSeeder::class);
        $this->call(MovieSeeder::class);
        $this->call(AnimeSeeder::class);
        $this->call(ClientSeeder::class);

    }
}

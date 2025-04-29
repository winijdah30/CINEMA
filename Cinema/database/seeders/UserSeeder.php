<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.fr',
        ]);
        User::factory()->create([
            'name' => 'client',
            'email' => 'client@client.fr',
        ]);
        $adminUser = User::find(1);
        $adminUser->assignRole('admin');
        $clientUser = User::find(2);
        $clientUser->assignRole('client');
        
        User::factory(9)->create();
    }
}

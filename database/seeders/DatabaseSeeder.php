<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@capitalimogroup.com',
            'password' => Hash::make('password'),
            'phone' => '+242 04 411 3436',
            'is_active' => true,
        ]);

        $this->call([
            ConfigurationSeeder::class,
            ServiceSeeder::class,
            MembreEquipeSeeder::class,
            TemoignageSeeder::class,
            BienSeeder::class,
        ]);
    }
}

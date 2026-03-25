<?php

namespace Database\Factories;

use App\Models\Bien;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BienFactory extends Factory
{
    protected $model = Bien::class;

    public function definition(): array
    {
        $types = ['maison', 'villa', 'appartement', 'local', 'terrain'];
        $transactions = ['vente', 'location'];
        $zones = ['Centre-ville', 'Périphérie', 'Ouenzé', 'Poto-Poto', 'Bacongo', 'Mfilou'];
        $quartiers = ['Centre-ville', 'Ouenzé', 'Poto-Poto', 'Bacongo', 'Mfilou', 'Talisman'];

        $type = fake()->randomElement($types);
        $transaction = fake()->randomElement($transactions);
        $titre = fake()->sentence(4);

        return [
            'titre' => $titre,
            'slug' => Str::slug($titre),
            'description' => fake()->paragraphs(3, true),
            'prix' => fake()->numberBetween(50000000, 500000000),
            'surface' => fake()->numberBetween(50, 500),
            'pieces' => fake()->numberBetween(2, 10),
            'chambres' => fake()->numberBetween(1, 5),
            'salle_de_bain' => fake()->numberBetween(1, 3),
            'etage' => $type === 'appartement' ? fake()->numberBetween(1, 10) : null,
            'type' => $type,
            'transaction' => $transaction,
            'zone' => fake()->randomElement($zones),
            'quartier' => fake()->randomElement($quartiers),
            'reference' => 'CIG-' . strtoupper(substr($type, 0, 1)) . '-' . fake()->unique()->numberBetween(100, 999),
            'statut' => fake()->randomElement(['disponible', 'vendu', 'reserve']),
            'en_vedette' => fake()->boolean(20),
            'caracteristiques' => fake()->randomElements(
                ['Piscine', 'Jardin', 'Garage', 'Climatisation', 'Sécurité', 'Cuisine équipée', 'Terrasse', 'Balcon'],
                fake()->numberBetween(2, 5)
            ),
            'vue_count' => fake()->numberBetween(0, 1000),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\MembreEquipe;
use Illuminate\Database\Seeder;

class MembreEquipeSeeder extends Seeder
{
    public function run(): void
    {
        $membres = [
            [
                'prenom' => 'Julio',
                'nom' => 'KIBONGUI',
                'poste' => 'Président Directeur Général',
                'email' => 'julio.kibongui@capitalimogroup.com',
                'telephone' => '+242 04 411 3436',
                'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400',
                'description' => 'Fondateur de Capital Immo Group, Julio KIBONGUI cumule plus de 15 ans d\'expérience dans l\'immobilier au Congo.',
                'ordre' => 1,
            ],
            [
                'prenom' => 'Sarah',
                'nom' => 'MOUSSAVOU',
                'poste' => 'Directrice Commerciale',
                'email' => 'sarah.moussavou@capitalimogroup.com',
                'telephone' => '+242 04 411 3437',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400',
                'description' => 'Experte en négociation immobilière, Sarah supervise l\'ensemble des transactions.',
                'ordre' => 2,
            ],
            [
                'prenom' => 'Marc',
                'nom' => 'NGOMA',
                'poste' => 'Responsable Gestion Locative',
                'email' => 'marc.ngoma@capitalimogroup.com',
                'telephone' => '+242 04 411 3438',
                'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400',
                'description' => 'Marc assure la gestion quotidienne des biens en location.',
                'ordre' => 3,
            ],
            [
                'prenom' => 'Grace',
                'nom' => 'MAKITA',
                'poste' => 'Conseillère Patrimoniale',
                'email' => 'grace.makita@capitalimogroup.com',
                'telephone' => '+242 04 411 3439',
                'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400',
                'description' => 'Spécialisée en accompagnement patrimonial, Grace aide les investisseurs.',
                'ordre' => 4,
            ],
        ];

        foreach ($membres as $membre) {
            MembreEquipe::create($membre);
        }
    }
}

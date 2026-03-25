<?php

namespace Database\Seeders;

use App\Models\Bien;
use App\Models\BienImage;
use Illuminate\Database\Seeder;

class BienSeeder extends Seeder
{
    public function run(): void
    {
        $biens = [
            [
                'titre' => 'Villa Luxueuse avec Piscine',
                'description' => 'Magnifique villa contemporaine située dans un quartier résidentiel prestigieux de Brazzaville. Cette propriété d\'exception offre des finitions haut de gamme, de vastes espaces de vie et un jardin paysager avec piscine privée.',
                'prix' => 450000000,
                'surface' => 450,
                'pieces' => 8,
                'chambres' => 5,
                'salle_de_bain' => 4,
                'etage' => 2,
                'type' => 'villa',
                'transaction' => 'vente',
                'zone' => 'Centre-ville',
                'quartier' => 'Ouenzé',
                'statut' => 'disponible',
                'en_vedette' => true,
                'caracteristiques' => ['Piscine', 'Jardin paysager', 'Garage 2 voitures', 'Climatisation', 'Sécurité 24/7', 'Cuisine équipée'],
                'images' => [
                    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800',
                ],
            ],
            [
                'titre' => 'Appartement Moderne Centre-Ville',
                'description' => 'Bel appartement rénové situé au cœur de Brazzaville, à proximité de toutes commodités. Lumineux et fonctionnel, il offre un cadre de vie idéal pour jeunes actifs ou couples.',
                'prix' => 75000000,
                'surface' => 85,
                'pieces' => 3,
                'chambres' => 2,
                'salle_de_bain' => 1,
                'etage' => 4,
                'type' => 'appartement',
                'transaction' => 'vente',
                'zone' => 'Centre-ville',
                'quartier' => 'Poto-Poto',
                'statut' => 'disponible',
                'en_vedette' => true,
                'caracteristiques' => ['Ascenseur', 'Parking', 'Balcon', 'Cuisine équipée', 'Interphone'],
                'images' => [
                    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
                ],
            ],
            [
                'titre' => 'Local Commercial Stratégique',
                'description' => 'Excellent emplacement pour ce local commercial situé sur une artère passante. Grande vitrine, espace modulable, parfait pour commerce de détail, restaurant ou bureau.',
                'prix' => 150000,
                'surface' => 120,
                'pieces' => 2,
                'chambres' => 0,
                'salle_de_bain' => 1,
                'etage' => 0,
                'type' => 'local',
                'transaction' => 'location',
                'zone' => 'Centre-ville',
                'quartier' => 'Bacongo',
                'statut' => 'disponible',
                'en_vedette' => true,
                'caracteristiques' => ['Grande vitrine', 'Fort passage', 'Parking client', 'Climatisation', 'Alarme'],
                'images' => [
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
                    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800',
                ],
            ],
            [
                'titre' => 'Maison Familiale avec Jardin',
                'description' => 'Charmante maison familiale dans un environnement calme et verdoyant. Parfait pour une famille avec enfants, elle dispose d\'un grand jardin arboré et d\'un espace de jeux.',
                'prix' => 185000000,
                'surface' => 200,
                'pieces' => 6,
                'chambres' => 4,
                'salle_de_bain' => 2,
                'etage' => 1,
                'type' => 'maison',
                'transaction' => 'vente',
                'zone' => 'Périphérie',
                'quartier' => 'Mfilou',
                'statut' => 'disponible',
                'en_vedette' => false,
                'caracteristiques' => ['Jardin arboré', 'Garage', 'Terrasse', 'Cuisine équipée', 'Bureau'],
                'images' => [
                    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
                    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800',
                ],
            ],
            [
                'titre' => 'Terrain Constructible 1000m²',
                'description' => 'Superbe terrain plat et viabilisé, idéal pour construction de votre maison de rêve. Situé dans un secteur en plein développement avec vue panoramique.',
                'prix' => 95000000,
                'surface' => 1000,
                'pieces' => 0,
                'chambres' => 0,
                'salle_de_bain' => 0,
                'type' => 'terrain',
                'transaction' => 'vente',
                'zone' => 'Périphérie',
                'quartier' => 'Talisman',
                'statut' => 'disponible',
                'en_vedette' => false,
                'caracteristiques' => ['Viabilisé', 'Terrain plat', 'Vue panoramique', 'Accès routier', 'Borné'],
                'images' => [
                    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800',
                    'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=800',
                ],
            ],
            [
                'titre' => 'Appartement Haut Standing',
                'description' => 'Superbe appartement de standing dans une résidence sécurisée avec piscine commune. Prestations de qualité, matériaux nobles, grande terrasse avec vue.',
                'prix' => 280000,
                'surface' => 110,
                'pieces' => 4,
                'chambres' => 3,
                'salle_de_bain' => 2,
                'etage' => 3,
                'type' => 'appartement',
                'transaction' => 'location',
                'zone' => 'Centre-ville',
                'quartier' => 'Ouenzé',
                'statut' => 'disponible',
                'en_vedette' => true,
                'caracteristiques' => ['Piscine commune', 'Terrasse', 'Parking', 'Cave', 'Gardien', 'Climatisation'],
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800',
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800',
                ],
            ],
        ];

        foreach ($biens as $bienData) {
            $images = $bienData['images'] ?? [];
            unset($bienData['images']);

            $bien = Bien::create($bienData);

            foreach ($images as $index => $url) {
                BienImage::create([
                    'bien_id' => $bien->id,
                    'url' => $url,
                    'ordre' => $index,
                ]);
            }
        }
    }
}

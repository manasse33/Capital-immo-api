<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'titre' => 'Vente de Biens',
                'description' => 'Achetez ou vendez votre bien en toute sérénité avec notre accompagnement expert.',
                'description_longue' => 'Capital Immo Group vous accompagne dans toutes les étapes de votre projet de vente immobilière. De l\'estimation précise de votre bien à la signature finale, notre équipe d\'experts met son savoir-faire à votre service.',
                'icon' => 'Home',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800',
                'avantages' => [
                    'Estimation gratuite et réaliste du marché',
                    'Visibilité maximale sur nos canaux de diffusion',
                    'Accompagnement juridique complet',
                    'Négociation experte pour obtenir le meilleur prix',
                    'Suivi personnalisé tout au long de la transaction',
                ],
                'cta' => 'Estimer mon bien',
                'ordre' => 1,
            ],
            [
                'titre' => 'Location Résidentielle & Commerciale',
                'description' => 'Trouvez le bien locatif parfait ou confiez-nous la location de votre propriété.',
                'description_longue' => 'Notre service de location couvre l\'ensemble du marché résidentiel et commercial de Brazzaville et ses environs. Pour les locataires, nous sélectionnons des biens de qualité correspondant à vos critères.',
                'icon' => 'Key',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'avantages' => [
                    'Large choix de biens dans tous les quartiers',
                    'Sélection rigoureuse des locataires',
                    'Baux conformes à la législation en vigueur',
                    'États des lieux détaillés et photographiés',
                    'Assistance en cas de litige',
                ],
                'cta' => 'Voir les biens à louer',
                'ordre' => 2,
            ],
            [
                'titre' => 'Gestion Locative',
                'description' => 'Déléguez la gestion complète de votre bien immobilier à des professionnels.',
                'description_longue' => 'Notre service de gestion locative vous libère de toutes les contraintes liées à la location de votre bien. Nous nous occupons de tout : recherche et sélection des locataires, encaissement des loyers, gestion des impayés.',
                'icon' => 'Building2',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800',
                'avantages' => [
                    'Encaissement garanti des loyers',
                    'Gestion complète des démarches administratives',
                    'Entretien et maintenance du bien',
                    'Relation locataire prise en charge',
                    'Reporting mensuel détaillé',
                ],
                'cta' => 'En savoir plus',
                'ordre' => 3,
            ],
            [
                'titre' => 'Accompagnement Patrimonial',
                'description' => 'Construisez et faites fructifier votre patrimoine immobilier avec nos conseils.',
                'description_longue' => 'L\'accompagnement patrimonial de Capital Immo Group s\'adresse aux investisseurs souhaitant développer leur patrimoine immobilier au Congo. Nos conseillers analysent votre situation pour vous proposer des stratégies adaptées.',
                'icon' => 'TrendingUp',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                'avantages' => [
                    'Analyse personnalisée de votre situation',
                    'Identification des meilleures opportunités',
                    'Stratégie d\'investissement sur mesure',
                    'Optimisation fiscale de vos acquisitions',
                    'Suivi et réévaluation régulière',
                ],
                'cta' => 'Prendre rendez-vous',
                'ordre' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

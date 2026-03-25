<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // Entreprise
            ['key' => 'entreprise.nom', 'value' => 'Capital Immo Group', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Nom de l\'entreprise'],
            ['key' => 'entreprise.slogan', 'value' => 'Plus qu\'un bien immobilier, nous trouvons le lieu où commence votre histoire.', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Slogan'],
            ['key' => 'entreprise.adresse', 'value' => 'Rue Monseigneur Biéchy 2015, Brazzaville, République du Congo', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Adresse'],
            ['key' => 'entreprise.telephone', 'value' => '+242 04 411 3436', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Téléphone'],
            ['key' => 'entreprise.whatsapp', 'value' => '+242 04 411 3436', 'type' => 'string', 'group' => 'entreprise', 'label' => 'WhatsApp'],
            ['key' => 'entreprise.email', 'value' => 'contact@capitalimogroup.com', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Email'],
            ['key' => 'entreprise.facebook', 'value' => '@capitalimogroup01', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Facebook'],
            ['key' => 'entreprise.facebook_url', 'value' => 'https://facebook.com/capitalimogroup01', 'type' => 'string', 'group' => 'entreprise', 'label' => 'URL Facebook'],
            ['key' => 'entreprise.description', 'value' => 'Votre partenaire de confiance en immobilier au Congo.', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Description courte'],
            ['key' => 'entreprise.histoire', 'value' => 'Fondee en 2011 par Julio KIBONGUI, Capital Immo Group est nee d\'une vision : professionnaliser le secteur immobilier en Republique du Congo et offrir aux clients un service a la hauteur de leurs attentes.', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Histoire'],
            ['key' => 'entreprise.mission', 'value' => 'Accompagner nos clients avec professionnalisme et integrite dans la realisation de leurs projets immobiliers, en leur offrant un service personnalise et des solutions adaptees a leurs besoins.', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Mission'],
            ['key' => 'entreprise.date_creation', 'value' => 2011, 'type' => 'integer', 'group' => 'entreprise', 'label' => 'Date de creation'],
            ['key' => 'entreprise.clients_satisfaits', 'value' => 500, 'type' => 'integer', 'group' => 'entreprise', 'label' => 'Clients satisfaits'],
            ['key' => 'entreprise.hero_image_url', 'value' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Image hero'],
            ['key' => 'entreprise.about_image_url', 'value' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800', 'type' => 'string', 'group' => 'entreprise', 'label' => 'Image a propos'],
            ['key' => 'entreprise.valeurs', 'value' => json_encode([
                ['titre' => 'Confiance', 'description' => 'La transparence et l\'honnetete sont au coeur de chaque relation que nous etablissons avec nos clients.', 'icon' => 'Shield'],
                ['titre' => 'Proximite', 'description' => 'Nous connaissons intimement le marche immobilier congolais et maintenons une relation personnalisee avec chaque client.', 'icon' => 'Heart'],
                ['titre' => 'Excellence', 'description' => 'Nous visons l\'excellence dans chaque service rendu, de la premiere prise de contact a la finalisation de la transaction.', 'icon' => 'Award'],
                ['titre' => 'Transparence', 'description' => 'Nos honoraires sont clairs, nos processus sont expliques, et nous communiquons regulierement sur l\'avancement de votre dossier.', 'icon' => 'Eye'],
            ]), 'type' => 'json', 'group' => 'entreprise', 'label' => 'Valeurs'],
            ['key' => 'entreprise.horaires', 'value' => json_encode([
                'lundi' => '08:00 - 17:00',
                'mardi' => '08:00 - 17:00',
                'mercredi' => '08:00 - 17:00',
                'jeudi' => '08:00 - 17:00',
                'vendredi' => '08:00 - 17:00',
                'samedi' => '09:00 - 13:00',
                'dimanche' => 'Fermé',
            ]), 'type' => 'json', 'group' => 'entreprise', 'label' => 'Horaires'],
            ['key' => 'entreprise.coordonnees', 'value' => json_encode(['lat' => -4.2634, 'lng' => 15.2429]), 'type' => 'json', 'group' => 'entreprise', 'label' => 'Coordonnées GPS'],
        ];

        foreach ($configs as $config) {
            Configuration::create($config);
        }
    }
}

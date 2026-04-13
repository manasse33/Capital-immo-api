<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("config.{$key}", 3600, function () use ($key, $default) {
            $config = self::where('key', $key)->first();
            return $config ? self::castValue($config->value, $config->type) : $default;
        });
    }

    public static function set(string $key, $value, string $type = 'string', string $group = 'general', string $label = null): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
                'label' => $label ?? $key,
            ]
        );
        Cache::forget("config.{$key}");
    }

    private static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    public static function getEntrepriseInfo(): array
    {
        return [
            'nom' => self::get('entreprise.nom', 'Capital Immo Group'),
            'slogan' => self::get('entreprise.slogan', 'Plus qu\'un bien immobilier, nous trouvons le lieu où commence votre histoire.'),
            'adresse' => self::get('entreprise.adresse', 'Rue Monseigneur Biéchy 2015, Brazzaville'),
            'telephone' => self::get('entreprise.telephone', '+242 04 411 3436'),
            'whatsapp' => self::get('entreprise.whatsapp', '+242 04 411 3436'),
            'email' => self::get('entreprise.email', 'contact@capital-immo-group.com'),
            'facebook' => self::get('entreprise.facebook', '@capitalimogroup01'),
            'facebook_url' => self::get('entreprise.facebook_url', 'https://facebook.com/capitalimogroup01'),
            'description' => self::get('entreprise.description', 'Votre partenaire de confiance en immobilier au Congo.'),
            'histoire' => self::get('entreprise.histoire', 'Fondee en 2011 par Julio KIBONGUI, Capital Immo Group est nee d\'une vision : professionnaliser le secteur immobilier en Republique du Congo et offrir aux clients un service a la hauteur de leurs attentes.'),
            'mission' => self::get('entreprise.mission', 'Accompagner nos clients avec professionnalisme et integrite dans la realisation de leurs projets immobiliers, en leur offrant un service personnalise et des solutions adaptees a leurs besoins.'),
            'date_creation' => self::get('entreprise.date_creation', 2011),
            'clients_satisfaits' => self::get('entreprise.clients_satisfaits', 500),
            'hero_image_url' => self::get('entreprise.hero_image_url', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920'),
            'about_image_url' => self::get('entreprise.about_image_url', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800'),
            'valeurs' => self::get('entreprise.valeurs', [
                ['titre' => 'Confiance', 'description' => 'La transparence et l\'honnetete sont au coeur de chaque relation que nous etablissons avec nos clients.', 'icon' => 'Shield'],
                ['titre' => 'Proximite', 'description' => 'Nous connaissons intimement le marche immobilier congolais et maintenons une relation personnalisee avec chaque client.', 'icon' => 'Heart'],
                ['titre' => 'Excellence', 'description' => 'Nous visons l\'excellence dans chaque service rendu, de la premiere prise de contact a la finalisation de la transaction.', 'icon' => 'Award'],
                ['titre' => 'Transparence', 'description' => 'Nos honoraires sont clairs, nos processus sont expliques, et nous communiquons regulierement sur l\'avancement de votre dossier.', 'icon' => 'Eye'],
            ]),
            'horaires' => self::get('entreprise.horaires', [
                'lundi' => '08:00 - 17:00',
                'mardi' => '08:00 - 17:00',
                'mercredi' => '08:00 - 17:00',
                'jeudi' => '08:00 - 17:00',
                'vendredi' => '08:00 - 17:00',
                'samedi' => '09:00 - 13:00',
                'dimanche' => 'Fermé',
            ]),
            'coordonnees' => self::get('entreprise.coordonnees', [
                'lat' => -4.2634,
                'lng' => 15.2429,
            ]),
        ];
    }
}

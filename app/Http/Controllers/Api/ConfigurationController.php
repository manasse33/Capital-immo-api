<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfigurationController extends Controller
{
    public function index(Request $request)
    {
        $query = Configuration::query();

        if ($request->has('group')) {
            $query->where('group', $request->group);
        }

        $configs = $query->get();

        return response()->json($configs);
    }

    public function show($key)
    {
        $value = Configuration::get($key);

        if ($value === null) {
            return response()->json(['message' => 'Configuration non trouvée.'], 404);
        }

        return response()->json(['key' => $key, 'value' => $value]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:configurations',
            'value' => 'required',
            'type' => 'required|in:string,integer,float,boolean,json,array',
            'group' => 'required|string|max:50',
            'label' => 'required|string|max:255',
        ]);

        Configuration::set(
            $validated['key'],
            $validated['value'],
            $validated['type'],
            $validated['group'],
            $validated['label']
        );

        return response()->json(['message' => 'Configuration créée.'], 201);
    }

    public function update(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'required',
            'type' => 'sometimes|in:string,integer,float,boolean,json,array',
            'group' => 'sometimes|string|max:50',
            'label' => 'sometimes|string|max:255',
        ]);

        $config = Configuration::where('key', $key)->first();

        if (!$config) {
            return response()->json(['message' => 'Configuration non trouvée.'], 404);
        }

        Configuration::set(
            $key,
            $validated['value'],
            $validated['type'] ?? $config->type,
            $validated['group'] ?? $config->group,
            $validated['label'] ?? $config->label
        );

        return response()->json(['message' => 'Configuration mise à jour.']);
    }

    public function destroy($key)
    {
        $config = Configuration::where('key', $key)->first();

        if (!$config) {
            return response()->json(['message' => 'Configuration non trouvée.'], 404);
        }

        $config->delete();
        Cache::forget("config.{$key}");

        return response()->json(['message' => 'Configuration supprimée.']);
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.key' => 'required|string',
            'configs.*.value' => 'required',
            'configs.*.type' => 'sometimes|in:string,integer,float,boolean,json,array',
        ]);

        foreach ($validated['configs'] as $config) {
            Configuration::set(
                $config['key'],
                $config['value'],
                $config['type'] ?? 'string'
            );
        }

        return response()->json(['message' => 'Configurations mises à jour.']);
    }

    public function entreprise()
    {
        return response()->json(Configuration::getEntrepriseInfo());
    }

    public function updateEntreprise(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'slogan' => 'sometimes|string|max:500',
            'adresse' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:50',
            'whatsapp' => 'sometimes|string|max:50',
            'email' => 'sometimes|email|max:255',
            'facebook' => 'sometimes|string|max:100',
            'facebook_url' => 'sometimes|url|max:255',
            'description' => 'sometimes|string|max:500',
            'histoire' => 'sometimes|string',
            'mission' => 'sometimes|string',
            'date_creation' => 'sometimes|integer|min:1800|max:2100',
            'clients_satisfaits' => 'sometimes|integer|min:0',
            'hero_image_url' => 'sometimes|url|max:500',
            'about_image_url' => 'sometimes|url|max:500',
            'horaires' => 'sometimes|array',
            'coordonnees' => 'sometimes|array',
            'coordonnees.lat' => 'required_with:coordonnees|numeric',
            'coordonnees.lng' => 'required_with:coordonnees|numeric',
            'valeurs' => 'sometimes|array',
            'valeurs.*.titre' => 'required_with:valeurs|string|max:100',
            'valeurs.*.description' => 'required_with:valeurs|string|max:500',
            'valeurs.*.icon' => 'required_with:valeurs|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            if (is_array($value)) {
                $type = 'json';
            } elseif (in_array($key, ['date_creation', 'clients_satisfaits'], true)) {
                $type = 'integer';
            } else {
                $type = 'string';
            }
            Configuration::set("entreprise.{$key}", $value, $type, 'entreprise');
        }

        return response()->json([
            'message' => 'Informations entreprise mises à jour.',
            'data' => Configuration::getEntrepriseInfo(),
        ]);
    }

    public function clearCache()
    {
        Cache::flush();

        return response()->json(['message' => 'Cache vidé avec succès.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BienController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Bien::with(['images', 'user']);

        // Filtres
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        } else {
            $query->disponible();
        }

        if ($request->boolean('en_vedette')) {
            $query->enVedette();
        }

        $query->filter($request->only([
            'type', 'transaction', 'zone', 'prix_min', 'prix_max', 'surface_min', 'search'
        ]));

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $biens = $request->has('per_page') 
            ? $query->paginate($request->get('per_page', 12))
            : $query->get();

        return response()->json($biens);
    }

    public function show($id)
    {
        $bien = Bien::with(['images', 'user'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        // Incrémenter le compteur de vues (sans recharger le modèle)
        $bien->incrementVueCount();

        return response()->json($bien);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|integer|min:0',
            'surface' => 'required|integer|min:0',
            'pieces' => 'nullable|integer|min:0',
            'chambres' => 'nullable|integer|min:0',
            'salle_de_bain' => 'nullable|integer|min:0',
            'etage' => 'nullable|integer|min:0',
            'type' => 'required|in:maison,villa,appartement,local,terrain',
            'transaction' => 'required|in:vente,location',
            'location_period' => 'required_if:transaction,location|nullable|in:mensuel,journalier',
            'zone' => 'required|string|max:100',
            'quartier' => 'required|string|max:100',
            'reference' => 'nullable|string|max:50|unique:biens',
            'statut' => 'nullable|in:disponible,vendu,reserve',
            'en_vedette' => 'nullable|boolean',
            'caracteristiques' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['statut'] = $validated['statut'] ?? 'disponible';
        $validated['en_vedette'] = $validated['en_vedette'] ?? false;
        if ($validated['transaction'] !== 'location') {
            $validated['location_period'] = null;
        }

        DB::beginTransaction();

        try {
            $bien = Bien::create($validated);

            // Upload des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $url = $this->imageService->upload($image, 'biens/' . $bien->id);
                    $bien->images()->create([
                        'url' => $url,
                        'ordre' => $index,
                    ]);
                }
            }

            DB::commit();

            return response()->json($bien->load('images'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la création: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Bien $bien)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'prix' => 'sometimes|integer|min:0',
            'surface' => 'sometimes|integer|min:0',
            'pieces' => 'nullable|integer|min:0',
            'chambres' => 'nullable|integer|min:0',
            'salle_de_bain' => 'nullable|integer|min:0',
            'etage' => 'nullable|integer|min:0',
            'type' => 'sometimes|in:maison,villa,appartement,local,terrain',
            'transaction' => 'sometimes|in:vente,location',
            'location_period' => 'required_if:transaction,location|nullable|in:mensuel,journalier',
            'zone' => 'sometimes|string|max:100',
            'quartier' => 'sometimes|string|max:100',
            'statut' => 'sometimes|in:disponible,vendu,reserve',
            'en_vedette' => 'nullable|boolean',
            'caracteristiques' => 'nullable|array',
            'replace_images' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $replaceImages = $request->boolean('replace_images');
        unset($validated['replace_images']);

        if (array_key_exists('transaction', $validated) && $validated['transaction'] !== 'location') {
            $validated['location_period'] = null;
        }

        DB::beginTransaction();

        try {
            $bien->update($validated);

            // Upload des nouvelles images
            if ($request->hasFile('images')) {
                if ($replaceImages) {
                    foreach ($bien->images as $image) {
                        $this->imageService->delete($image->url);
                    }
                    $bien->images()->delete();
                    $maxOrder = -1;
                } else {
                    $maxOrder = $bien->images()->max('ordre') ?? -1;
                }

                foreach ($request->file('images') as $index => $image) {
                    $url = $this->imageService->upload($image, 'biens/' . $bien->id);
                    $bien->images()->create([
                        'url' => $url,
                        'ordre' => $maxOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return response()->json($bien->load('images'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Bien $bien)
    {
        DB::beginTransaction();

        try {
            // Supprimer les images du stockage
            foreach ($bien->images as $image) {
                $this->imageService->delete($image->url);
            }

            $bien->delete();

            DB::commit();

            return response()->json(['message' => 'Bien supprimé avec succès.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la suppression: ' . $e->getMessage()], 500);
        }
    }

    public function similaires(Bien $bien)
    {
        $similaires = Bien::with('images')
            ->where('id', '!=', $bien->id)
            ->where('type', $bien->type)
            ->where('statut', 'disponible')
            ->limit(3)
            ->get();

        return response()->json($similaires);
    }

    public function toggleVedette(Bien $bien)
    {
        $bien->update(['en_vedette' => !$bien->en_vedette]);

        return response()->json([
            'message' => $bien->en_vedette ? 'Bien mis en vedette.' : 'Bien retiré des vedettes.',
            'en_vedette' => $bien->en_vedette,
        ]);
    }

    public function updateStatut(Request $request, Bien $bien)
    {
        $validated = $request->validate([
            'statut' => 'required|in:disponible,vendu,reserve',
        ]);

        $bien->update($validated);

        return response()->json([
            'message' => 'Statut mis à jour.',
            'statut' => $bien->statut,
        ]);
    }

    public function reorderImages(Request $request, Bien $bien)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:bien_images,id',
            'images.*.ordre' => 'required|integer|min:0',
        ]);

        foreach ($validated['images'] as $imageData) {
            $bien->images()->where('id', $imageData['id'])->update(['ordre' => $imageData['ordre']]);
        }

        return response()->json($bien->images()->orderBy('ordre')->get());
    }

    public function deleteImage(Bien $bien, $imageId)
    {
        $image = $bien->images()->findOrFail($imageId);
        
        $this->imageService->delete($image->url);
        $image->delete();

        return response()->json(['message' => 'Image supprimée.']);
    }

    public function stats()
    {
        $stats = [
            'total' => Bien::count(),
            'disponibles' => Bien::disponible()->count(),
            'vendus' => Bien::where('statut', 'vendu')->count(),
            'reserves' => Bien::where('statut', 'reserve')->count(),
            'en_vedette' => Bien::enVedette()->count(),
            'par_type' => Bien::selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type'),
            'par_transaction' => Bien::selectRaw('transaction, count(*) as count')->groupBy('transaction')->pluck('count', 'transaction'),
            'vues_total' => Bien::sum('vue_count'),
        ];

        return response()->json($stats);
    }

    public function filters()
    {
        $zones = Bien::query()
            ->select('zone')
            ->whereNotNull('zone')
            ->distinct()
            ->orderBy('zone')
            ->pluck('zone')
            ->values();

        $quartiers = Bien::query()
            ->select('quartier')
            ->whereNotNull('quartier')
            ->distinct()
            ->orderBy('quartier')
            ->pluck('quartier')
            ->values();

        $types = [
            ['value' => 'maison', 'label' => 'Maison'],
            ['value' => 'villa', 'label' => 'Villa'],
            ['value' => 'appartement', 'label' => 'Appartement'],
            ['value' => 'local', 'label' => 'Local commercial'],
            ['value' => 'terrain', 'label' => 'Terrain'],
        ];

        $transactions = [
            ['value' => 'vente', 'label' => 'Vente'],
            ['value' => 'location', 'label' => 'Location'],
        ];

        return response()->json([
            'zones' => $zones,
            'quartiers' => $quartiers,
            'types' => $types,
            'transactions' => $transactions,
        ]);
    }
}

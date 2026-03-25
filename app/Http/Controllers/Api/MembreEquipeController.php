<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MembreEquipe;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class MembreEquipeController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = MembreEquipe::query();

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $query->ordered();

        $membres = $request->has('per_page')
            ? $query->paginate($request->get('per_page', 10))
            : $query->get();

        return response()->json($membres);
    }

    public function show(MembreEquipe $membre)
    {
        return response()->json($membre);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'poste' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'telephone' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageService->upload($request->file('photo'), 'equipe');
        }

        $validated['is_active'] = $validated['is_active'] ?? true;

        $membre = MembreEquipe::create($validated);

        return response()->json($membre, 201);
    }

    public function update(Request $request, MembreEquipe $membre)
    {
        $validated = $request->validate([
            'prenom' => 'sometimes|string|max:100',
            'nom' => 'sometimes|string|max:100',
            'poste' => 'sometimes|string|max:150',
            'email' => 'nullable|email|max:150',
            'telephone' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($membre->photo) {
                $this->imageService->delete($membre->photo);
            }
            $validated['photo'] = $this->imageService->upload($request->file('photo'), 'equipe');
        }

        $membre->update($validated);

        return response()->json($membre);
    }

    public function destroy(MembreEquipe $membre)
    {
        if ($membre->photo) {
            $this->imageService->delete($membre->photo);
        }

        $membre->delete();

        return response()->json(['message' => 'Membre supprimé avec succès.']);
    }

    public function toggleActive(MembreEquipe $membre)
    {
        $membre->update(['is_active' => !$membre->is_active]);

        return response()->json([
            'message' => $membre->is_active ? 'Membre activé.' : 'Membre désactivé.',
            'is_active' => $membre->is_active,
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'membres' => 'required|array',
            'membres.*.id' => 'required|exists:membres_equipe,id',
            'membres.*.ordre' => 'required|integer|min:0',
        ]);

        foreach ($validated['membres'] as $data) {
            MembreEquipe::where('id', $data['id'])->update(['ordre' => $data['ordre']]);
        }

        return response()->json(['message' => 'Ordre mis à jour.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Temoignage;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class TemoignageController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Temoignage::query();

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $query->ordered();

        $temoignages = $request->has('per_page')
            ? $query->paginate($request->get('per_page', 10))
            : $query->get();

        return response()->json($temoignages);
    }

    public function show(Temoignage $temoignage)
    {
        return response()->json($temoignage);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'initiale' => 'nullable|string|max:2',
            'role' => 'required|string|max:100',
            'message' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'note' => 'nullable|integer|min:1|max:5',
            'is_active' => 'nullable|boolean',
            'ordre' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->imageService->upload($request->file('avatar'), 'temoignages');
        }

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['note'] = $validated['note'] ?? 5;

        $temoignage = Temoignage::create($validated);

        return response()->json($temoignage, 201);
    }

    public function update(Request $request, Temoignage $temoignage)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'initiale' => 'nullable|string|max:2',
            'role' => 'sometimes|string|max:100',
            'message' => 'sometimes|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'note' => 'nullable|integer|min:1|max:5',
            'is_active' => 'nullable|boolean',
            'ordre' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($temoignage->avatar) {
                $this->imageService->delete($temoignage->avatar);
            }
            $validated['avatar'] = $this->imageService->upload($request->file('avatar'), 'temoignages');
        }

        $temoignage->update($validated);

        return response()->json($temoignage);
    }

    public function destroy(Temoignage $temoignage)
    {
        if ($temoignage->avatar) {
            $this->imageService->delete($temoignage->avatar);
        }

        $temoignage->delete();

        return response()->json(['message' => 'Témoignage supprimé avec succès.']);
    }

    public function toggleActive(Temoignage $temoignage)
    {
        $temoignage->update(['is_active' => !$temoignage->is_active]);

        return response()->json([
            'message' => $temoignage->is_active ? 'Témoignage activé.' : 'Témoignage désactivé.',
            'is_active' => $temoignage->is_active,
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'temoignages' => 'required|array',
            'temoignages.*.id' => 'required|exists:temoignages,id',
            'temoignages.*.ordre' => 'required|integer|min:0',
        ]);

        foreach ($validated['temoignages'] as $data) {
            Temoignage::where('id', $data['id'])->update(['ordre' => $data['ordre']]);
        }

        return response()->json(['message' => 'Ordre mis à jour.']);
    }
}

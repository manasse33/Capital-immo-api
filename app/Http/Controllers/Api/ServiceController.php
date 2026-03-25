<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $query->ordered();

        $services = $request->has('per_page')
            ? $query->paginate($request->get('per_page', 10))
            : $query->get();

        return response()->json($services);
    }

    public function show($id)
    {
        $service = Service::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        return response()->json($service);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'description_longue' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'avantages' => 'required|array',
            'avantages.*' => 'string|max:255',
            'cta' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['titre']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->imageService->upload($request->file('image'), 'services');
        }

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['cta'] = $validated['cta'] ?? 'En savoir plus';

        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'description_longue' => 'sometimes|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'avantages' => 'sometimes|array',
            'avantages.*' => 'string|max:255',
            'cta' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($validated['titre'])) {
            $validated['slug'] = Str::slug($validated['titre']);
        }

        if ($request->hasFile('image')) {
            if ($service->image) {
                $this->imageService->delete($service->image);
            }
            $validated['image'] = $this->imageService->upload($request->file('image'), 'services');
        }

        $service->update($validated);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            $this->imageService->delete($service->image);
        }

        $service->delete();

        return response()->json(['message' => 'Service supprimé avec succès.']);
    }

    public function toggleActive(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        return response()->json([
            'message' => $service->is_active ? 'Service activé.' : 'Service désactivé.',
            'is_active' => $service->is_active,
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.ordre' => 'required|integer|min:0',
        ]);

        foreach ($validated['services'] as $data) {
            Service::where('id', $data['id'])->update(['ordre' => $data['ordre']]);
        }

        return response()->json(['message' => 'Ordre mis à jour.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with('bien');

        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        if ($request->has('objet')) {
            $query->where('objet', $request->objet);
        }

        $query->recent();

        $contacts = $request->has('per_page')
            ? $query->paginate($request->get('per_page', 15))
            : $query->get();

        return response()->json($contacts);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact->load('bien'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'objet' => 'required|string|max:100',
            'message' => 'required|string',
            'bien_id' => 'nullable|exists:biens,id',
            'reference_bien' => 'nullable|string|max:50',
        ]);

        $contact = Contact::create($validated);

        // Envoyer un email de notification à l'admin
        // Mail::to(config('mail.admin_address'))->send(new NewContactNotification($contact));

        return response()->json([
            'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
            'contact' => $contact,
        ], 201);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'is_read' => 'nullable|boolean',
        ]);

        $contact->update($validated);

        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Message supprimé avec succès.']);
    }

    public function markAsRead(Contact $contact)
    {
        $contact->markAsRead();

        return response()->json([
            'message' => 'Message marqué comme lu.',
            'is_read' => true,
        ]);
    }

    public function markAsUnread(Contact $contact)
    {
        $contact->update(['is_read' => false]);

        return response()->json([
            'message' => 'Message marqué comme non lu.',
            'is_read' => false,
        ]);
    }

    public function stats()
    {
        $stats = [
            'total' => Contact::count(),
            'non_lus' => Contact::unread()->count(),
            'lus' => Contact::where('is_read', true)->count(),
            'par_objet' => Contact::selectRaw('objet, count(*) as count')
                ->groupBy('objet')
                ->pluck('count', 'objet'),
            'ce_mois' => Contact::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id',
        ]);

        Contact::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Messages supprimés avec succès.']);
    }

    public function bulkMarkAsRead(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id',
        ]);

        Contact::whereIn('id', $validated['ids'])->update(['is_read' => true]);

        return response()->json(['message' => 'Messages marqués comme lus.']);
    }
}

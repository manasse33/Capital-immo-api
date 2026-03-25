<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Contact;
use App\Models\Temoignage;
use App\Models\MembreEquipe;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'biens' => [
                'total' => Bien::count(),
                'disponibles' => Bien::disponible()->count(),
                'vendus' => Bien::where('statut', 'vendu')->count(),
                'reserves' => Bien::where('statut', 'reserve')->count(),
                'en_vedette' => Bien::enVedette()->count(),
                'vues_total' => Bien::sum('vue_count'),
            ],
            'contacts' => [
                'total' => Contact::count(),
                'non_lus' => Contact::unread()->count(),
                'ce_mois' => Contact::whereMonth('created_at', now()->month)->count(),
            ],
            'temoignages' => [
                'total' => Temoignage::count(),
                'actifs' => Temoignage::active()->count(),
            ],
            'equipe' => [
                'total' => MembreEquipe::count(),
                'actifs' => MembreEquipe::active()->count(),
            ],
            'services' => [
                'total' => Service::count(),
                'actifs' => Service::active()->count(),
            ],
        ];

        return response()->json($stats);
    }

    public function recentActivity()
    {
        $activity = [
            'derniers_biens' => Bien::with('images')->latest()->take(5)->get(),
            'derniers_contacts' => Contact::recent()->take(5)->get(),
            'biens_populaires' => Bien::with('images')->orderBy('vue_count', 'desc')->take(5)->get(),
        ];

        return response()->json($activity);
    }

    public function chartsData()
    {
        // Biens par mois (6 derniers mois)
        $biensParMois = Bien::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, count(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Contacts par mois
        $contactsParMois = Contact::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, count(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Biens par type
        $biensParType = Bien::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        // Biens par transaction
        $biensParTransaction = Bien::selectRaw('transaction, count(*) as total')
            ->groupBy('transaction')
            ->pluck('total', 'transaction');

        return response()->json([
            'biens_par_mois' => $biensParMois,
            'contacts_par_mois' => $contactsParMois,
            'biens_par_type' => $biensParType,
            'biens_par_transaction' => $biensParTransaction,
        ]);
    }
}

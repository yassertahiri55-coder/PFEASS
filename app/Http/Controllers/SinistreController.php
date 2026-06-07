<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Notification;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SinistreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        Log::info('SinistreController@index user', ['user' => $user]);
        if (! $user) {
            Log::warning('SinistreController@index: Non authentifié');

            return response()->json(['error' => 'Non authentifié'], 401);
        }
        if ($user->role === 'agent') {
            // L'agent voit uniquement ses sinistres assignés
            $sinistres = Sinistre::where('user_id', $user->id)->get();
            Log::info('SinistreController@index: agent, own sinistres', ['count' => $sinistres->count()]);
        } elseif ($user->role === 'client') {
            // Le client ne voit que ses sinistres
            $sinistres = Sinistre::where('client_id', $user->id)->get();
            Log::info('SinistreController@index: client, own sinistres', ['count' => $sinistres->count()]);
        } else {
            // Admin ou expert: tout voir
            $sinistres = Sinistre::all();
            Log::info('SinistreController@index: admin/expert, all sinistres', ['count' => $sinistres->count()]);
        }

        return response()->json($sinistres->values());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // (optionnel pour API REST, sinon retourner une vue de formulaire)
        return response()->json(['message' => 'Formulaire de création de sinistre']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'date_declaration' => 'required|date',
        ]);

        $creator = Auth::user();
        $client = $creator;

        $sinistreData = [
            ...$validated,
            'client_id' => $client->id,
        ];

        if ($creator->role === 'agent') {
            $sinistreData['user_id'] = $creator->id;
        }

        $sinistre = Sinistre::create($sinistreData);

        // Créer automatiquement un dossier lié à ce sinistre
        $numero = 'DS-'.date('Ymd-His').'-'.rand(1000, 9999);
        $statut = 'en_attente';
        $date_ouverture = $sinistre->date_declaration;
        $date_cloture = null;
        $dossier = Dossier::create([
            'numero' => $numero,
            'statut' => $statut,
            'date_ouverture' => $date_ouverture,
            'date_cloture' => $date_cloture,
            'sinistre_id' => $sinistre->id,
        ]);

        return response()->json([
            'sinistre' => $sinistre,
            'dossier' => $dossier,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sinistre = Sinistre::with(['user', 'dossiers', 'documents', 'client'])->findOrFail($id);
        $user = Auth::user();
        // Autoriser tous les agents, le client d'origine, l'expert ou l'admin
        if (
            ($user->role === 'agent' && $sinistre->user_id === $user->id) ||
            ($user->role === 'client' && $sinistre->client_id === $user->id) ||
            ($user->role === 'expert') ||
            ($user->role === 'admin')
        ) {
            return response()->json($sinistre);
        }
        abort(403, 'Accès refusé');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // (optionnel pour API REST, sinon retourner une vue de formulaire)
        return response()->json(['message' => 'Formulaire d\'édition de sinistre']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sinistre = Sinistre::findOrFail($id);
        $user = Auth::user();
        Log::info('Update sinistre: user', ['user_id' => $user->id, 'role' => $user->role]);
        // Autoriser le propriétaire du sinistre, le client, l'expert ou l'admin à modifier le statut
        if ($sinistre->user_id !== $user->id && $sinistre->client_id !== $user->id && $user->role !== 'expert' && $user->role !== 'admin') {
            Log::warning('Update sinistre: accès refusé', ['sinistre_user_id' => $sinistre->user_id, 'sinistre_client_id' => $sinistre->client_id, 'user_id' => $user->id, 'role' => $user->role]);
            abort(403, 'Accès refusé');
        }
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date_declaration' => 'sometimes|required|date',
            'statut' => 'sometimes|required|in:en_attente,en_cours,valide,refuse,transfere_expert',
        ]);
        Log::info('Update sinistre: validated', $validated);
        $sinistre->update($validated);
        Log::info('Update sinistre: after update', ['sinistre_id' => $sinistre->id, 'statut' => $sinistre->statut]);

        return response()->json($sinistre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sinistre = Sinistre::findOrFail($id);
        $user = Auth::user();
        if ($sinistre->user_id !== $user->id && $sinistre->client_id !== $user->id && $user->role !== 'expert') {
            abort(403, 'Accès refusé');
        }
        $sinistre->delete();

        return response()->json(['message' => 'Sinistre supprimé']);
    }

    /**
     * Envoyer tous les documents d'un sinistre à l'expert (role = 'expert')
     */
    public function envoyerDocumentsAExpert($id)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $role = strtolower(trim((string) $user->role));
        if ($role !== 'agent') {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        $sinistre = Sinistre::with('documents')->findOrFail($id);
        // Marquer le sinistre comme transféré à l'expert
        $sinistre->statut = 'transfere_expert';
        $sinistre->save();
        // Log et notification
        Log::info('Sinistre transféré à l\'expert', [
            'sinistre_id' => $sinistre->id,
            'documents' => $sinistre->documents->pluck('id'),
            'envoyeur' => $user->id,
        ]);

        return response()->json(['message' => 'Sinistre transféré à l\'expert', 'sinistre' => $sinistre, 'documents' => $sinistre->documents]);
    }

    public function agentStatistics()
    {
        $user = Auth::user();
        if (! $user) {
            Log::warning('agentStatistics: no authenticated user', ['headers' => request()->headers->all()]);

            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $role = strtolower(trim((string) $user->role));
        if ($role !== 'agent') {
            Log::warning('agentStatistics: access denied - wrong role', ['user_id' => $user->id, 'role' => $user->role]);

            return response()->json(['error' => 'Non autorisé pour ce rôle', 'role' => $user->role], 403);
        }

        // Regrouper les calculs lourds en une seule requête pour réduire les allers-retours DB
        $sinistreStats = DB::table('sinistres')
            ->selectRaw(
                "COUNT(*) as total_sinistres,
                SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as sinistres_en_attente,
                SUM(CASE WHEN statut = 'transfere_expert' THEN 1 ELSE 0 END) as sinistres_transfere_expert,
                SUM(CASE WHEN statut = 'valide' THEN 1 ELSE 0 END) as sinistres_valides,
                SUM(CASE WHEN statut = 'refuse' THEN 1 ELSE 0 END) as sinistres_refuses,
                COUNT(DISTINCT client_id) as clients_distincts"
            )->first();

        $totalSinistres = (int) ($sinistreStats->total_sinistres ?? 0);
        $sinistresEnAttente = (int) ($sinistreStats->sinistres_en_attente ?? 0);
        $sinistresTransfereExpert = (int) ($sinistreStats->sinistres_transfere_expert ?? 0);
        $sinistresValides = (int) ($sinistreStats->sinistres_valides ?? 0);
        $sinistresRefuses = (int) ($sinistreStats->sinistres_refuses ?? 0);
        $clientsDistincts = (int) ($sinistreStats->clients_distincts ?? 0);

        $totalDossiers = Dossier::count();
        $dossiersOuverts = Dossier::where('statut', 'en_attente')->count();
        $notificationsEnvoyees = Notification::count();

        return response()->json([
            'total_sinistres' => $totalSinistres,
            'sinistres_en_attente' => $sinistresEnAttente,
            'sinistres_transfere_expert' => $sinistresTransfereExpert,
            'sinistres_valides' => $sinistresValides,
            'sinistres_refuses' => $sinistresRefuses,
            'total_dossiers' => $totalDossiers,
            'dossiers_ouverts' => $dossiersOuverts,
            'clients_distincts' => $clientsDistincts,
            'notifications_envoyees' => $notificationsEnvoyees,
        ]);
    }
}

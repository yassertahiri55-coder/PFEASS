<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;
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
        if (!$user) {
            Log::warning('SinistreController@index: Non authentifié');
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        if ($user->role === 'agent') {
            // L'agent voit tous les sinistres
            $sinistres = Sinistre::all();
            Log::info('SinistreController@index: agent, all sinistres', ['count' => $sinistres->count()]);
        } else if ($user->role === 'client') {
            // Le client ne voit que ses sinistres
            $sinistres = Sinistre::where('client_id', $user->id)->get();
            Log::info('SinistreController@index: client, own sinistres', ['count' => $sinistres->count()]);
        } else {
            // Admin ou autre: tout voir
            $sinistres = Sinistre::all();
            Log::info('SinistreController@index: admin, all sinistres', ['count' => $sinistres->count()]);
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

        $client = Auth::user();
        // Trouver un agent (exemple: premier agent dispo)
        $agent = \App\Models\User::where('role', 'agent')->first();
        if (!$agent) {
            return response()->json(['error' => 'Aucun agent disponible'], 422);
        }

        $sinistre = Sinistre::create([
            ...$validated,
            'user_id' => $agent->id,
            'client_id' => $client->id,
        ]);

        // Créer automatiquement un dossier lié à ce sinistre
        $numero = 'DS-' . date('Ymd-His') . '-' . rand(1000,9999);
        $statut = 'en_attente';
        $date_ouverture = $sinistre->date_declaration;
        $date_cloture = null;
        $dossier = \App\Models\Dossier::create([
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
            ($user->role === 'agent') ||
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
        // Autoriser le propriétaire OU l'expert à modifier le statut
        if ($sinistre->user_id !== $user->id && $user->role !== 'expert') {
            Log::warning('Update sinistre: accès refusé', ['sinistre_user_id' => $sinistre->user_id, 'user_id' => $user->id, 'role' => $user->role]);
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
        if ($sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $sinistre->delete();
        return response()->json(['message' => 'Sinistre supprimé']);
    }
     /**
     * Envoyer tous les documents d'un sinistre à l'expert (role_id = 3)
     */
    public function envoyerDocumentsAExpert($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'agent') {
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
            'envoyeur' => $user->id
        ]);
        return response()->json(['message' => 'Sinistre transféré à l\'expert', 'sinistre' => $sinistre, 'documents' => $sinistre->documents]);
}
}
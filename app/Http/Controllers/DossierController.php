<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dossier;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;

class DossierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retourne tous les dossiers avec la relation sinistre
        return response()->json(Dossier::with('sinistre')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création de dossier']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,en_cours,termine,refuse',
            'sinistre_id' => 'required|exists:sinistres,id',
        ]);
        // Générer un numéro unique (ex: DS-YYYYMMDD-HHMMSS-rand)
        $numero = 'DS-' . date('Ymd-His') . '-' . rand(1000,9999);
        $date_ouverture = now()->toDateString();
        $date_cloture = null;
        // Permettre à l'agent de créer un dossier pour n'importe quel sinistre
        $sinistre = Sinistre::findOrFail($validated['sinistre_id']);
        $dossier = Dossier::create([
            'numero' => $numero,
            'statut' => $validated['statut'],
            'date_ouverture' => $date_ouverture,
            'date_cloture' => $date_cloture,
            'sinistre_id' => $validated['sinistre_id'],
        ]);
        return response()->json($dossier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dossier = Dossier::findOrFail($id);
        // Vérifier que le dossier appartient à un sinistre de l'utilisateur
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return response()->json($dossier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Formulaire d\'édition de dossier']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dossier = Dossier::findOrFail($id);
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $validated = $request->validate([
            'numero' => 'sometimes|required|string|unique:dossiers,numero,' . $dossier->id,
            'statut' => 'sometimes|required|in:en_attente,en_cours,termine,refuse',
            'date_ouverture' => 'sometimes|required|date',
            'date_cloture' => 'nullable|date',
        ]);
        $dossier->update($validated);
        return response()->json($dossier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dossier = Dossier::findOrFail($id);
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $dossier->delete();
        return response()->json(['message' => 'Dossier supprimé']);
    }
     /**
     * Valider un dossier (expert uniquement)
     */
    public function valider($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'expert') {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        $dossier = Dossier::findOrFail($id);
        $dossier->statut = 'termine';
        $dossier->date_cloture = now();
        $dossier->save();
        // Mettre à jour le statut du sinistre lié
        $sinistre = $dossier->sinistre;
        if ($sinistre) {
            $sinistre->statut = 'valide';
            $sinistre->save();
        }

        // Créer une notification pour l'agent
        $agentId = $dossier->sinistre->user_id ?? null;
        if ($agentId) {
            \App\Models\Notification::create([
                'user_id' => $agentId,
                'dossier_id' => $dossier->id,
                'type' => 'validation',
                'message' => "Votre dossier {$dossier->numero} a été validé par l'expert.",
                'lu' => false,
            ]);
        }

        return response()->json(['message' => 'Dossier validé', 'dossier' => $dossier]);
    }

    /**
     * Refuser un dossier (expert uniquement)
     */
    public function refuser($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'expert') {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        $dossier = Dossier::findOrFail($id);
        $dossier->statut = 'refuse';
        $dossier->date_cloture = now();
        $dossier->save();
        // Mettre à jour le statut du sinistre lié
        $sinistre = $dossier->sinistre;
        if ($sinistre) {
            $sinistre->statut = 'refuse';
            $sinistre->save();
        }

        // Créer une notification pour l'agent
        $agentId = $dossier->sinistre->user_id ?? null;
        if ($agentId) {
            \App\Models\Notification::create([
                'user_id' => $agentId,
                'dossier_id' => $dossier->id,
                'type' => 'refus',
                'message' => "Votre dossier {$dossier->numero} a été refusé par l'expert.",
                'lu' => false,
            ]);
        }

        return response()->json(['message' => 'Dossier refusé', 'dossier' => $dossier]);
    }
}

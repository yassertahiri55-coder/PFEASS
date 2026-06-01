<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\RendezVous;
use App\Models\Dossier;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affiche tous les rendez-vous liés aux dossiers de l'utilisateur connecté
        $sinistres = Sinistre::all()->where('user_id', Auth::id());
        $dossiers = Dossier::all()->whereIn('sinistre_id', $sinistres->pluck('id')->all());
        $rendezVous = RendezVous::all()->whereIn('dossier_id', $dossiers->pluck('id')->all());
        return response()->json($rendezVous->values());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création de rendez-vous']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:planifie,effectue,annule',
            'dossier_id' => 'required|exists:dossiers,id',
        ]);
        // Vérifier que le dossier appartient à l'utilisateur
        $dossier = Dossier::findOrFail($validated['dossier_id']);
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $rendezVous = RendezVous::create($validated);
        return response()->json($rendezVous, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        if ($rendezVous->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return response()->json($rendezVous);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Formulaire d\'édition de rendez-vous']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        if ($rendezVous->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'lieu' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'sometimes|required|in:planifie,effectue,annule',
        ]);
        $rendezVous->update($validated);
        return response()->json($rendezVous);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        if ($rendezVous->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $rendezVous->delete();
        return response()->json(['message' => 'Rendez-vous supprimé']);
    }
}

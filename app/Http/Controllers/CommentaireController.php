<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Commentaire;
use App\Models\Dossier;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affiche tous les commentaires liés aux dossiers de l'utilisateur connecté
        $sinistres = Sinistre::all()->where('user_id', Auth::id());
        $dossiers = Dossier::all()->whereIn('sinistre_id', $sinistres->pluck('id')->all());
        $commentaires = Commentaire::all()->whereIn('dossier_id', $dossiers->pluck('id')->all());
        return response()->json($commentaires->values());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création de commentaire']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenu' => 'required|string',
            'dossier_id' => 'required|exists:dossiers,id',
        ]);
        // Vérifier que le dossier appartient à l'utilisateur
        $dossier = Dossier::findOrFail($validated['dossier_id']);
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $commentaire = Commentaire::create([
            'contenu' => $validated['contenu'],
            'dossier_id' => $validated['dossier_id'],
            'user_id' => Auth::id(),
        ]);
        return response()->json($commentaire, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        if ($commentaire->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return response()->json($commentaire);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Formulaire d\'édition de commentaire']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        if ($commentaire->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $validated = $request->validate([
            'contenu' => 'sometimes|required|string',
        ]);
        $commentaire->update($validated);
        return response()->json($commentaire);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        if ($commentaire->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $commentaire->delete();
        return response()->json(['message' => 'Commentaire supprimé']);
    }
}

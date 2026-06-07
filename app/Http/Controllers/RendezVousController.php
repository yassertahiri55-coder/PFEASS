<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\RendezVous;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (in_array($user->role, ['agent', 'expert', 'admin'], true)) {
            return response()->json(RendezVous::all());
        }

        $sinistres = Sinistre::where('client_id', Auth::id())->get();
        $dossiers = Dossier::whereIn('sinistre_id', $sinistres->pluck('id')->all())->get();
        $rendezVous = RendezVous::whereIn('dossier_id', $dossiers->pluck('id')->all())->get();

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
        $user = Auth::user();
        Log::info('RendezVousController@store called', [
            'user_id' => $user?->id,
            'role' => $user?->role,
            'dossier_id' => $request->input('dossier_id'),
            'input' => $request->only(['date', 'lieu', 'description', 'statut', 'dossier_id']),
            'headers' => [
                'authorization' => $request->header('authorization'),
                'x-csrf-token' => $request->header('x-csrf-token'),
                'cookie' => $request->header('cookie'),
            ],
        ]);

        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:planifie,effectue,annule',
            'dossier_id' => 'required|exists:dossiers,id',
        ]);

        // Vérifier que le dossier appartient bien à l'utilisateur ou qu'il est agent/expert/admin
        $dossier = Dossier::findOrFail($validated['dossier_id']);

        if (! in_array($user->role, ['agent', 'expert', 'admin'], true) && $dossier->sinistre->client_id !== $user->id) {
            Log::warning('RendezVousController@store unauthorized', [
                'user_id' => $user?->id,
                'role' => $user?->role,
                'dossier_id' => $dossier->id,
                'sinistre_client_id' => $dossier->sinistre->client_id,
            ]);
            abort(403, 'Accès refusé');
        }

        try {
            $rendezVous = RendezVous::create($validated);
            Log::info('RendezVousController@store created', [
                'rendezvous_id' => $rendezVous->id,
                'dossier_id' => $dossier->id,
                'user_id' => $user?->id,
                'role' => $user?->role,
            ]);

            return response()->json($rendezVous, 201);
        } catch (\Throwable $exception) {
            Log::error('RendezVousController@store failed', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'user_id' => $user?->id,
                'role' => $user?->role,
                'dossier_id' => $validated['dossier_id'],
            ]);

            throw $exception;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $user = Auth::user();
        if (! in_array($user->role, ['agent', 'expert', 'admin'], true) && $rendezVous->dossier->sinistre->client_id !== $user->id) {
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
        $user = Auth::user();
        if (! in_array($user->role, ['agent', 'expert', 'admin'], true) && $rendezVous->dossier->sinistre->client_id !== $user->id) {
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
        $user = Auth::user();
        if (! in_array($user->role, ['agent', 'expert', 'admin'], true) && $rendezVous->dossier->sinistre->client_id !== $user->id) {
            abort(403, 'Accès refusé');
        }
        $rendezVous->delete();

        return response()->json(['message' => 'Rendez-vous supprimé']);
    }
}

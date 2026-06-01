<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Dossier;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affiche toutes les notifications liées aux dossiers de l'utilisateur connecté
        $sinistres = Sinistre::all()->where('user_id', Auth::id());
        $dossiers = Dossier::all()->whereIn('sinistre_id', $sinistres->pluck('id')->all());
        $notifications = Notification::all()->whereIn('dossier_id', $dossiers->pluck('id')->all());
        // On ne retourne que message et created_at
        $result = $notifications->map(function($n) {
            return [
                'id' => $n->id,
                'message' => $n->message,
                'created_at' => $n->created_at,
            ];
        });
        return response()->json($result->values());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création de notification']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'message' => 'required|string',
            'lu' => 'boolean',
            'dossier_id' => 'required|exists:dossiers,id',
        ]);
        // Vérifier que le dossier appartient à l'utilisateur
        $dossier = Dossier::findOrFail($validated['dossier_id']);
        if ($dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $notification = Notification::create($validated);
        return response()->json($notification, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Formulaire d\'édition de notification']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $validated = $request->validate([
            'type' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'lu' => 'sometimes|boolean',
        ]);
        $notification->update($validated);
        return response()->json($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->dossier->sinistre->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $notification->delete();
        return response()->json(['message' => 'Notification supprimée']);
    }
}

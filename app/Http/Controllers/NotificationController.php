<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affiche toutes les notifications destinées à l'utilisateur connecté
        $notifications = Notification::where('user_id', Auth::id())->get();
        $result = $notifications->map(function ($n) {
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
            'recipient_role' => 'required|string|in:client,expert,admin,agent',
        ]);

        // Récupérer le dossier lié
        $dossier = Dossier::findOrFail($validated['dossier_id']);

        // Autorisation : si l'émetteur est client, il ne peut envoyer que pour ses propres sinistres
        $sender = Auth::user();
        if ($sender->role === 'client' && $dossier->sinistre->client_id !== $sender->id) {
            abort(403, 'Accès refusé');
        }

        // Déterminer les destinataires selon le rôle demandé
        $recipientIds = [];
        if ($validated['recipient_role'] === 'client') {
            $recipientIds = [$dossier->sinistre->client_id];
        } elseif ($validated['recipient_role'] === 'expert') {
            $recipientIds = User::where('role', 'expert')->pluck('id')->all();
        } elseif ($validated['recipient_role'] === 'admin') {
            $recipientIds = User::where('role', 'admin')->pluck('id')->all();
        } elseif ($validated['recipient_role'] === 'agent') {
            $recipientIds = User::where('role', 'agent')->pluck('id')->all();
        }

        if (empty($recipientIds)) {
            return response()->json(['error' => 'Aucun destinataire trouvé'], 422);
        }

        $notifications = [];
        // Inclure le nom/role de l'émetteur dans le message pour que le destinataire sache qui a envoyé
        $senderPrefix = $sender->prenom ? ($sender->prenom.' '.$sender->name) : $sender->name;
        $senderPrefix .= ' ('.$sender->role.')';

        foreach ($recipientIds as $userId) {
            $notifications[] = Notification::create([
                'type' => $validated['type'],
                'message' => $senderPrefix.': '.$validated['message'],
                'is_read' => $validated['lu'] ?? false,
                'dossier_id' => $validated['dossier_id'],
                'user_id' => $userId,
            ]);
        }

        return response()->json(['message' => 'Notification(s) envoyée(s)', 'count' => count($notifications)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id !== Auth::id()) {
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
        if ($notification->user_id !== Auth::id()) {
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
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $notification->delete();

        return response()->json(['message' => 'Notification supprimée']);
    }
}

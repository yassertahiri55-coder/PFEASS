<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Notification;
use App\Models\Sinistre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $sinistres = Sinistre::with(['user', 'client', 'dossiers'])->get();
        $dossiers = Dossier::with('sinistre')->get();
        $agents = User::where('role', 'agent')->get();
        $experts = User::where('role', 'expert')->get();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_dashboard', compact('sinistres', 'dossiers', 'agents', 'experts', 'notifications'));
    }

    public function updateSinistreStatus(Request $request, $id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,en_cours,valide,refuse,transfere_expert',
        ]);

        $sinistre = Sinistre::findOrFail($id);
        $sinistre->statut = $validated['statut'];
        $sinistre->save();

        return redirect()->back()->with('success', 'Statut du sinistre mis à jour.');
    }

    public function sendNotification(Request $request)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $validated = $request->validate([
            'recipient_role' => 'required|in:client,expert,admin,agent',
            'dossier_id' => 'required|exists:dossiers,id',
            'message' => 'required|string',
            'type' => 'nullable|string|max:255',
        ]);

        $recipientRole = $validated['recipient_role'];

        $recipientIds = [];
        if ($recipientRole === 'client') {
            $dossier = Dossier::findOrFail($validated['dossier_id']);
            $recipientIds = [$dossier->sinistre->client_id];
        } else {
            $recipientIds = User::where('role', $recipientRole)->pluck('id')->all();
        }

        foreach ($recipientIds as $rid) {
            Notification::create([
                'type' => $validated['type'] ?? 'info',
                'message' => ($user->prenom ? $user->prenom.' ' : '').$user->name.' (admin): '.$validated['message'],
                'is_read' => false,
                'dossier_id' => $validated['dossier_id'],
                'user_id' => $rid,
            ]);
        }

        return redirect()->back()->with('success', 'Notification(s) envoyée(s)');
    }

    public function users()
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        $pendingUsers = User::where('status', 'pending')->orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users', 'pendingUsers'));
    }

    public function createUser()
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        return view('admin.users_create');
    }

    public function storeUser(Request $request)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:30',
            'pays' => 'nullable|string|max:100',
            'date_naissance' => 'nullable|date',
            'role' => 'required|in:client,agent,expert,admin',
            'status' => 'required|in:pending,active,rejected',
        ]);

        User::create([
            'name' => $validated['name'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'] ?? null,
            'pays' => $validated['pays'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé.');
    }

    public function editUser($id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $editUser = User::findOrFail($id);

        return view('admin.users_edit', compact('editUser'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $editUser = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$editUser->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:30',
            'pays' => 'nullable|string|max:100',
            'date_naissance' => 'nullable|date',
            'role' => 'required|in:client,agent,expert,admin',
            'status' => 'required|in:pending,active,rejected',
        ]);

        $data = [
            'name' => $validated['name'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'pays' => $validated['pays'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $editUser->update($data);

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroyUser($id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $destroyUser = User::findOrFail($id);
        if ($destroyUser->id === $user->id) {
            return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $destroyUser->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé.');
    }

    public function approveUser($id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $pendingUser = User::findOrFail($id);
        $pendingUser->status = 'active';
        $pendingUser->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur approuvé.');
    }

    public function rejectUser($id)
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $pendingUser = User::findOrFail($id);
        $pendingUser->status = 'rejected';
        $pendingUser->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur refusé.');
    }
}

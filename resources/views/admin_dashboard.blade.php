@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <!-- Glassmorphic Header Card -->
            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(17, 24, 39, 0.45);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 54px; height: 54px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" width="36" height="36" style="border-radius:50%; background:#fff; padding:2px;">
                        </div>
                        <div>
                            <h3 class="mb-1 text-white">Espace Administrateur</h3>
                            <p class="mb-0 text-muted small">Bienvenue, <span class="text-primary fw-bold">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</span></p>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary text-white text-uppercase" style="letter-spacing:1px; font-size:0.75rem;">Mode Admin</span>
                    </div>
                </div>
            </div>

            <!-- Modern Interactive Functionality Buttons (Tabs) -->
            <div class="nav-pills-custom shadow-sm mb-4">
                <button class="nav-link-btn active" onclick="switchAdminTab('tab-sinistres', this)">
                    <i class="bi bi-folder2-open" style="font-size:1.1rem;"></i>
                    📂 Gestion des Dossiers
                </button>
                <button class="nav-link-btn" onclick="switchAdminTab('tab-utilisateurs', this)">
                    <i class="bi bi-people-fill" style="font-size:1.1rem;"></i>
                    👥 Gestion des Utilisateurs
                </button>
                <button class="nav-link-btn" onclick="switchAdminTab('tab-notifications', this)">
                    <i class="bi bi-send-fill" style="font-size:1.1rem;"></i>
                    ✉️ Envoyer Notification
                </button>
                <button class="nav-link-btn" onclick="switchAdminTab('tab-received-notifs', this)">
                    <i class="bi bi-bell-fill" style="font-size:1.1rem;"></i>
                    🔔 Notifications Reçues
                </button>
            </div>

            <!-- Status Alerts -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Tab Content 1: Gestion des Dossiers -->
            <div id="tab-sinistres" class="admin-tab-content">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-gear-fill text-primary" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Changer le statut d'un sinistre</h4>
                    </div>
                    <p class="text-muted small mb-4">Modifiez rapidement l'état d'un sinistre dans le flux de traitement global.</p>
                    
                    <form method="POST" action="{{ route('admin.sinistre.updateStatus', ['id' => 0]) }}" id="sinistre-status-form" class="action-card">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Sélectionner le sinistre</label>
                            <select name="sinistre_id" id="sinistre-select" class="form-select" required>
                                <option value="">-- Choisir un sinistre --</option>
                                @isset($sinistres)
                                    @foreach($sinistres as $s)
                                        <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->titre }} (statut actuel: {{ $s->statut }})</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Nouveau statut à appliquer</label>
                            <select name="statut" class="form-select" required>
                                <option value="en_attente">en_attente</option>
                                <option value="en_cours">en_cours</option>
                                <option value="valide">valide</option>
                                <option value="refuse">refuse</option>
                                <option value="transfere_expert">transfere_expert</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill"></i> Mettre à jour le statut
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tab Content 2: Gestion des Utilisateurs -->
            <div id="tab-utilisateurs" class="admin-tab-content d-none">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people text-warning" style="font-size:1.5rem;"></i>
                            <h4 class="mb-0 text-white">Gestion des Comptes Utilisateurs</h4>
                        </div>
                        <a href="{{ route('admin.users') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-right-short"></i> Ouvrir le panneau de gestion complet
                        </a>
                    </div>
                    <p class="text-muted small mb-4">Créez, modifiez, supprimez et approuvez des comptes d'experts, d'agents et de clients.</p>
                    
                    <div class="row g-4 text-center mt-2">
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 border border-secondary bg-dark bg-opacity-20 d-flex flex-column align-items-center justify-content-center" style="min-height:180px;">
                                <i class="bi bi-person-plus text-primary mb-3" style="font-size:2.5rem;"></i>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm px-4">Créer un utilisateur</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 border border-secondary bg-dark bg-opacity-20 d-flex flex-column align-items-center justify-content-center" style="min-height:180px;">
                                <i class="bi bi-shield-check text-success mb-3" style="font-size:2.5rem;"></i>
                                <a href="{{ route('admin.users') }}" class="btn btn-success btn-sm px-4">Approuver comptes en attente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content 3: Envoyer Notification -->
            <div id="tab-notifications" class="admin-tab-content d-none">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-chat-dots-fill text-success" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Envoyer une notification directe</h4>
                    </div>
                    <p class="text-muted small mb-4">Envoyez des messages de suivi ou de relance aux utilisateurs associés à un dossier.</p>
                    
                    <form method="POST" action="{{ route('admin.notifications.send') }}" class="action-card">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dossier ciblé</label>
                                <select name="dossier_id" class="form-select" required>
                                    <option value="">-- Choisir un dossier --</option>
                                    @isset($dossiers)
                                        @foreach($dossiers as $d)
                                            <option value="{{ $d->id }}">Dossier #{{ $d->id }} (Réf: {{ $d->numero }})</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Destinataire (rôle)</label>
                                <select name="recipient_role" class="form-select" required>
                                    <option value="agent">Agent</option>
                                    <option value="expert">Expert</option>
                                    <option value="client">Client</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type ou Sujet (optionnel)</label>
                            <input type="text" name="type" placeholder="Ex: Rappel, Validation, Document Manquant" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Message à envoyer</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Écrivez le message de votre notification..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-send"></i> Envoyer la notification
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tab Content 4: Notifications reçues -->
            <div id="tab-received-notifs" class="admin-tab-content d-none">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-bell-fill text-info" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Notifications reçues de la plateforme</h4>
                    </div>
                    <p class="text-muted small mb-4">Journal récent des notifications destinées à l'administration.</p>
                    
                    <div class="action-card p-0 overflow-hidden" style="max-height: 400px; overflow-y: auto;">
                        @if(isset($notifications) && $notifications->count())
                            <ul class="list-group list-group-flush bg-transparent">
                                @foreach($notifications as $notification)
                                    <li class="list-group-item bg-transparent text-white border-secondary border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <span class="badge bg-info text-white mb-2">{{ ucfirst($notification->type ?? 'info') }}</span>
                                                <p class="mb-0" style="font-size:0.925rem; color:#cbd5e1;">{{ $notification->message }}</p>
                                            </div>
                                            <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox mb-2" style="font-size:2rem;"></i>
                                <p class="mb-0">Aucune notification pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
    // Tab switching javascript logic
    function switchAdminTab(tabId, btnElement) {
        // Hide all tabs
        document.querySelectorAll('.admin-tab-content').forEach(function(tab) {
            tab.classList.add('d-none');
        });
        
        // Deactivate all nav buttons
        document.querySelectorAll('.nav-link-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.classList.remove('d-none');
        }
        
        // Activate clicked button
        if (btnElement) {
            btnElement.classList.add('active');
        }
    }

    // Submit handler for sinistre status
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('sinistre-status-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const select = document.getElementById('sinistre-select');
                if (!select || !select.value) {
                    e.preventDefault();
                    alert('Veuillez choisir un sinistre.');
                    return;
                }
                const id = select.value;
                form.action = form.action.replace('/0', '/' + id);
            });
        }
    });
</script>
@endsection

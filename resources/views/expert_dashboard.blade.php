@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Glassmorphic Header Card -->
            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(17, 24, 39, 0.45);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 54px; height: 54px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Expert" width="36" height="36" style="border-radius:50%; background:#fff; padding:2px;">
                        </div>
                        <div>
                            <h3 class="mb-1 text-white">Espace Expert Agrée</h3>
                            <p class="mb-0 text-muted small">Bienvenue, <span class="text-success fw-bold">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</span></p>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success text-white text-uppercase" style="letter-spacing:1px; font-size:0.75rem;">Mode Expert</span>
                    </div>
                </div>
            </div>

            <!-- Modern Interactive Functionality Buttons (Tabs) -->
            <div class="nav-pills-custom shadow-sm mb-4">
                <button class="nav-link-btn active" onclick="switchExpertTab('tab-dossiers-cours', this)">
                    <i class="bi bi-clock-history" style="font-size:1.1rem;"></i>
                    📋 Dossiers en Cours
                </button>
                <button class="nav-link-btn" onclick="switchExpertTab('tab-rendezvous-plan', this)">
                    <i class="bi bi-calendar-event" style="font-size:1.1rem;"></i>
                    📅 Planifier Rendez-vous
                </button>
                <button class="nav-link-btn" onclick="switchExpertTab('tab-historique', this)">
                    <i class="bi bi-check-all" style="font-size:1.1rem;"></i>
                    ✅ Historique des Décisions
                </button>

            </div>

            @php
                $debugRows = [];
                $allDossiers = \App\Models\Dossier::with(['sinistre.user', 'sinistre.client', 'commentaires.user'])
                    ->get();

                $dossiers = $allDossiers->filter(function($dossier) use (&$debugRows) {
                    $reason = '';
                    if (!$dossier->sinistre) {
                        $reason = 'Pas de sinistre lié';
                    } elseif (!$dossier->sinistre->client) {
                        $reason = 'Sinistre sans client lié';
                    } elseif (!in_array($dossier->sinistre->statut, ['transfere_expert', 'valide', 'refuse'], true)) {
                        $reason = 'Sinistre non transféré/validé/refusé (statut=' . ($dossier->sinistre->statut ?? 'null') . ')';
                    }
                    if ($reason) {
                        $debugRows[] = [
                            'id' => $dossier->id,
                            'numero' => $dossier->numero,
                            'statut' => $dossier->statut,
                            'sinistre_id' => $dossier->sinistre?->id ?? null,
                            'sinistre_user_id' => $dossier->sinistre?->client?->id ?? null,
                            'sinistre_user_role' => $dossier->sinistre?->client?->role ?? 'null',
                            'sinistre_statut' => $dossier->sinistre?->statut ?? 'null',
                            'reason' => $reason
                        ];
                    }
                    return !$reason;
                });

                $dossiersEnCours = $dossiers->filter(fn($d) => in_array($d->statut, ['en_attente', 'en_cours']));
                $dossiersTermines = $dossiers->filter(fn($d) => $d->statut === 'termine');
                $dossiersRefuses = $dossiers->filter(fn($d) => $d->statut === 'refuse');
                $dossiersHistorique = $dossiers->filter(fn($d) => in_array($d->statut, ['termine', 'refuse']));
            @endphp

            <!-- Tab Content 1: Dossiers en cours -->
            <div id="tab-dossiers-cours" class="expert-tab-content">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-file-earmark-medical text-primary" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Dossiers à évaluer</h4>
                    </div>
                    <p class="text-muted small mb-4">Vérifiez les pièces justificatives, rédigez vos commentaires d'expertise et validez ou refusez les dossiers transmis.</p>

                    @if($dossiersEnCours->count() === 0)
                        <div class="text-center p-5 text-muted action-card">
                            <i class="bi bi-clipboard-x mb-2" style="font-size:2.5rem;"></i>
                            <p class="mb-0">Aucun dossier en cours à traiter pour le moment.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Numéro</th>
                                        <th>Statut</th>
                                        <th>Sinistre lié</th>
                                        <th class="text-end">Actions de décision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiersEnCours as $dossier)
                                        <tr>
                                            <td><span class="text-muted fw-bold">#{{ $dossier->id }}</span></td>
                                            <td><div class="fw-bold text-white">{{ $dossier->numero }}</div></td>
                                            <td>
                                                @if($dossier->statut=='en_attente')
                                                    <span class="badge bg-warning text-white">En attente</span>
                                                @elseif($dossier->statut=='en_cours')
                                                    <span class="badge bg-info text-white">En cours</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">{{ $dossier->statut }}</span>
                                                @endif
                                            </td>
                                            <td><span class="text-light">{{ $dossier->sinistre->titre ?? '-' }}</span></td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <button class="btn btn-success btn-sm valider-btn py-1.5 px-3" data-dossier-id="{{ $dossier->id }}" data-action="/dossiers/{{ $dossier->id }}/valider" onclick="return confirm('Valider ce dossier ?')">
                                                        <i class="bi bi-check-circle"></i> Valider
                                                    </button>
                                                    <button class="btn btn-danger btn-sm refuser-btn py-1.5 px-3" data-dossier-id="{{ $dossier->id }}" data-action="/dossiers/{{ $dossier->id }}/refuser" onclick="return confirm('Refuser ce dossier ?')">
                                                        <i class="bi bi-x-circle"></i> Refuser
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expanded details for dossiers in progress -->
                                        <tr>
                                            <td colspan="5" class="bg-dark bg-opacity-30 p-3 border-top-0 rounded-bottom">
                                                <div class="p-3 rounded bg-black bg-opacity-30 border border-secondary">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="fw-semibold text-warning mb-2"><i class="bi bi-info-circle me-1"></i>Informations du sinistre</div>
                                                            <div class="small mb-1 text-light"><b>Titre :</b> {{ $dossier->sinistre->titre ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Type :</b> {{ $dossier->sinistre->type ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Description :</b> {{ $dossier->sinistre->description ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Date déclaration :</b> {{ $dossier->sinistre->date_declaration ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Documents joints :</b>
                                                                @php $docs = $dossier->sinistre->documents ?? collect(); @endphp
                                                                @if($docs->count())
                                                                    <ul class="mb-1 ps-3 text-light mt-1">
                                                                        @foreach($docs as $doc)
                                                                            <li><a href="{{ url('storage/'.$doc->chemin) }}" target="_blank" class="text-info"><i class="bi bi-file-earmark-arrow-down"></i> {{ $doc->nom }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span class="text-muted">Aucun document joint</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-warning mb-2"><i class="bi bi-chat-left-text me-1"></i>Commentaires/rapports d'expertise</div>
                                                            <div class="mb-2 p-2 bg-dark bg-opacity-40 rounded" style="max-height:140px; overflow-y:auto;">
                                                                @php $commentaires = $dossier->commentaires()->with('user')->latest()->get(); @endphp
                                                                @forelse($commentaires as $commentaire)
                                                                    <div class="mb-2 small border-start border-3 ps-2 @if($commentaire->user_id == Auth::id()) border-success @else border-secondary @endif">
                                                                        <span class="fw-bold text-white">{{ $commentaire->user->prenom ?? '' }} {{ $commentaire->user->name ?? '' }}</span>
                                                                        <span class="text-muted" style="font-size:0.75rem;">({{ $commentaire->created_at->format('d/m/Y H:i') }})</span> :<br>
                                                                        <span class="text-light">{{ $commentaire->contenu }}</span>
                                                                    </div>
                                                                @empty
                                                                    <span class="text-muted small">Aucun commentaire pour ce dossier.</span>
                                                                @endforelse
                                                            </div>
                                                            <form method="POST" action="{{ route('commentaires.store') }}" class="d-flex align-items-center gap-2 mt-2">
                                                                @csrf
                                                                <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                                                                <input type="text" name="contenu" class="form-control form-control-sm mb-0" placeholder="Ajouter un commentaire ou rapport..." required maxlength="500">
                                                                <button class="btn btn-outline-primary btn-sm px-3" type="submit"><i class="bi bi-send"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab Content 2: Planifier rendez-vous -->
            <div id="tab-rendezvous-plan" class="expert-tab-content d-none">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-calendar-plus text-primary" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Planifier des Rendez-vous Clients</h4>
                    </div>
                    <p class="text-muted small mb-4">Ces formulaires s'activent pour les dossiers validés afin de fixer une date de rendez-vous d'indemnisation ou d'examen.</p>

                    @if($dossiersTermines->count() === 0)
                        <div class="text-center p-5 text-muted action-card">
                            <i class="bi bi-calendar-x mb-2" style="font-size:2.5rem;"></i>
                            <p class="mb-0">Aucun dossier validé disponible pour planifier un rendez-vous.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-4">
                            @foreach($dossiersTermines as $dossier)
                                <div class="p-4 rounded border border-success bg-dark bg-opacity-10">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2 flex-wrap gap-2">
                                        <div>
                                            <span class="fw-bold text-white" style="font-size:1.1rem;">Dossier {{ $dossier->numero }}</span>
                                            <span class="badge bg-success text-white ms-2">Validé ✓</span>
                                        </div>
                                        <div class="small text-muted">Sinistre : {{ $dossier->sinistre->titre ?? '-' }}</div>
                                    </div>
                                    <form class="rendezvous-form" data-dossier-id="{{ $dossier->id }}">
                                        <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                                        <input type="hidden" name="statut" value="planifie">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Date et heure</label>
                                                <input type="datetime-local" name="date" class="form-control" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Lieu du rendez-vous</label>
                                                <input type="text" name="lieu" placeholder="Adresse physique ou en ligne" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Description / Consignes</label>
                                                <input type="text" name="description" placeholder="Instructions pour le client" class="form-control" maxlength="500">
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-3 align-items-center flex-wrap">
                                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                                <i class="bi bi-calendar2-check-fill"></i> Programmer le rendez-vous
                                            </button>
                                            <span class="text-muted small"><i class="bi bi-info-circle"></i> Le client recevra instantanément l'invitation dans son espace React.</span>
                                        </div>
                                        <div class="rendezvous-result mt-2"></div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab Content 3: Historique -->
            <div id="tab-historique" class="expert-tab-content d-none">
                <div class="card p-4 border-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-journal-check text-success" style="font-size:1.5rem;"></i>
                        <h4 class="mb-0 text-white">Décisions prises</h4>
                    </div>
                    <p class="text-muted small mb-4">Historique des dossiers validés ou refusés par votre compte d'expert.</p>

                    @if($dossiersHistorique->count() === 0)
                        <div class="text-center p-5 text-muted action-card">
                            <i class="bi bi-collection-play mb-2" style="font-size:2.5rem;"></i>
                            <p class="mb-0">Aucun dossier archivé dans l'historique.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Réf Dossier</th>
                                        <th>Sinistre</th>
                                        <th>Client</th>
                                        <th>Date décision</th>
                                        <th class="text-end">Résultat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiersHistorique as $dossier)
                                        <tr>
                                            <td><span class="fw-bold text-white">{{ $dossier->numero }}</span></td>
                                            <td><span class="text-light">{{ $dossier->sinistre->titre ?? '-' }}</span></td>
                                            <td><span class="text-light">{{ $dossier->sinistre->client->prenom ?? '' }} {{ $dossier->sinistre->client->name ?? '' }}</span></td>
                                            <td><span class="text-muted">{{ $dossier->updated_at->format('d/m/Y H:i') }}</span></td>
                                            <td class="text-end">
                                                @if($dossier->statut === 'termine')
                                                    <span class="badge bg-success text-white">Validé ✓</span>
                                                @elseif($dossier->statut === 'refuse')
                                                    <span class="badge bg-danger text-white">Refusé ✗</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">{{ $dossier->statut }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Expanded details for archived dossiers -->
                                        <tr>
                                            <td colspan="5" class="bg-dark bg-opacity-30 p-3 border-top-0 rounded-bottom">
                                                <div class="p-3 rounded bg-black bg-opacity-30 border border-success border-opacity-25">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="fw-semibold text-success mb-2"><i class="bi bi-info-circle me-1"></i>Détails du sinistre archivé</div>
                                                            <div class="small mb-1 text-light"><b>Titre :</b> {{ $dossier->sinistre->titre ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Type :</b> {{ $dossier->sinistre->type ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Description :</b> {{ $dossier->sinistre->description ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Date de déclaration :</b> {{ $dossier->sinistre->date_declaration ?? '-' }}</div>
                                                            <div class="small mb-1 text-light"><b>Fichiers justificatifs :</b>
                                                                @php $docs = $dossier->sinistre->documents ?? collect(); @endphp
                                                                @if($docs->count())
                                                                    <ul class="mb-0 ps-3 text-light mt-1">
                                                                        @foreach($docs as $doc)
                                                                            <li><a href="{{ url('storage/'.$doc->chemin) }}" target="_blank" class="text-info"><i class="bi bi-file-earmark-arrow-down"></i> {{ $doc->nom }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span class="text-muted">Aucun document joint</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-success mb-2"><i class="bi bi-journal-text me-1"></i>Commentaires & rapports rédigés</div>
                                                            <div class="p-2 bg-dark bg-opacity-40 rounded" style="max-height:160px; overflow-y:auto;">
                                                                @php $commentaires = $dossier->commentaires()->with('user')->latest()->get(); @endphp
                                                                @forelse($commentaires as $commentaire)
                                                                    <div class="mb-2 small border-start border-3 ps-2 @if($commentaire->user_id == Auth::id()) border-success @else border-secondary @endif">
                                                                        <span class="fw-bold text-white">{{ $commentaire->user->prenom ?? '' }} {{ $commentaire->user->name ?? '' }}</span>
                                                                        <span class="text-muted" style="font-size:0.75rem;">({{ $commentaire->created_at->format('d/m/Y H:i') }})</span> :<br>
                                                                        <span class="text-light">{{ $commentaire->contenu }}</span>
                                                                    </div>
                                                                @empty
                                                                    <span class="text-muted small">Aucun commentaire rédigé pour ce dossier.</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="mt-3 text-muted small">
                                                                <i class="bi bi-lock-fill"></i> Ce dossier est archivé. Les rapports et commentaires sont verrouillés en lecture seule.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>



        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
    // Tab switching javascript logic for Expert Dashboard
    function switchExpertTab(tabId, btnElement) {
        document.querySelectorAll('.expert-tab-content').forEach(function(tab) {
            tab.classList.add('d-none');
        });
        document.querySelectorAll('.nav-link-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.classList.remove('d-none');
        }
        if (btnElement) {
            btnElement.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Gestion AJAX de validation de dossier
        document.querySelectorAll('.valider-btn').forEach(function (btn) {
            btn.addEventListener('click', async function (event) {
                event.preventDefault();
                const url = this.dataset.action;
                
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({ error: 'Erreur serveur' }));
                        throw new Error(errorData?.error || errorData?.message || 'Erreur lors de la validation');
                    }
                    
                    alert('✅ Dossier validé! Le formulaire de rendez-vous est maintenant disponible.');
                    location.reload();
                } catch (error) {
                    alert('❌ Erreur: ' + error.message);
                }
            });
        });

        // Gestion AJAX de refus de dossier
        document.querySelectorAll('.refuser-btn').forEach(function (btn) {
            btn.addEventListener('click', async function (event) {
                event.preventDefault();
                const url = this.dataset.action;
                
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({ error: 'Erreur serveur' }));
                        throw new Error(errorData?.error || errorData?.message || 'Erreur lors du refus');
                    }
                    
                    alert('Dossier refusé.');
                    location.reload();
                } catch (error) {
                    alert('❌ Erreur: ' + error.message);
                }
            });
        });

        // Gestion de la soumission du formulaire de rendez-vous
        document.querySelectorAll('.rendezvous-form').forEach(function (form) {
            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                const resultElement = form.querySelector('.rendezvous-result');
                resultElement.innerHTML = '<div class="alert alert-info">Envoi du rendez-vous...</div>';

                const data = {
                    dossier_id: form.querySelector('[name="dossier_id"]').value,
                    statut: form.querySelector('[name="statut"]').value,
                    date: form.querySelector('[name="date"]').value,
                    lieu: form.querySelector('[name="lieu"]').value,
                    description: form.querySelector('[name="description"]').value,
                };

                try {
                    const response = await fetch('{{ url('/rendezvous') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(data),
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => null);
                        const message = errorData?.message || (errorData?.errors ? Object.values(errorData.errors).flat().join(' ') : 'Erreur lors de la planification.');
                        throw new Error(message);
                    }

                    const json = await response.json();
                    resultElement.innerHTML = '<div class="alert alert-success">Rendez-vous planifié avec succès pour ' + (json.date ? new Date(json.date).toLocaleString() : 'la date sélectionnée') + '.</div>';
                    form.reset();
                } catch (error) {
                    resultElement.innerHTML = '<div class="alert alert-danger">' + (error.message || 'Impossible de planifier le rendez-vous.') + '</div>';
                }
            });
        });
    });
</script>

@endsection

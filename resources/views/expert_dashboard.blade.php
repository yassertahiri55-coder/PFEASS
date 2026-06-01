@extends('layouts.app')

@section('content')
<style>
    body { background: linear-gradient(120deg, #f8fafc 0%, #e6f7ef 100%) !important; font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success bg-gradient text-white rounded-top-4 d-flex align-items-center" style="min-height:60px;">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Expert" width="38" height="38" class="me-2" style="background:#fff;border-radius:50%;padding:3px;box-shadow:0 1px 4px #b0eacd;">
                    <span style="font-size:1.3rem;letter-spacing:1px;">Espace Expert</span>
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    <h4 class="mb-3">Bienvenue, <span class="text-success">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</span></h4>
                    <p class="mb-4">Vous pouvez <b>valider</b> ou <b>refuser</b> les dossiers transmis par les agents.<br><span class="text-muted small">Interface claire, rapide et moderne.</span></p>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm">
                                <i class="bi bi-journal-check text-primary" style="font-size:2rem;"></i>
                                <div class="fw-bold mt-2 mb-3">Validation des dossiers</div>
                                <table class="table table-bordered table-hover align-middle bg-white">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Statut</th>
                                            <th>Sinistre</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $debugRows = [];
                                            $dossiers = \App\Models\Dossier::with(['sinistre.user'])
                                                ->get()
                                                ->filter(function($dossier) use (&$debugRows) {
                                                    $reason = '';
                                                    if (!$dossier->sinistre) {
                                                        $reason = 'Pas de sinistre lié';
                                                    } elseif (!$dossier->sinistre->user) {
                                                        $reason = 'Sinistre sans user';
                                                    } elseif ($dossier->sinistre->user->role !== 'agent') {
                                                        $reason = 'User du sinistre n\'est pas agent (role=' . ($dossier->sinistre->user->role ?? 'null') . ')';
                                                    } elseif ($dossier->sinistre->statut !== 'transfere_expert') {
                                                        $reason = 'Sinistre non transféré à l\'expert (statut=' . ($dossier->sinistre->statut ?? 'null') . ')';
                                                    }
                                                    if ($reason) {
                                                        $debugRows[] = [
                                                            'id' => $dossier->id,
                                                            'numero' => $dossier->numero,
                                                            'statut' => $dossier->statut,
                                                            'sinistre_id' => $dossier->sinistre->id ?? null,
                                                            'sinistre_user_id' => $dossier->sinistre->user->id ?? null,
                                                            'sinistre_user_role' => $dossier->sinistre->user->role ?? 'null',
                                                            'sinistre_statut' => $dossier->sinistre->statut ?? 'null',
                                                            'reason' => $reason
                                                        ];
                                                    }
                                                    return !$reason;
                                                });
                                        @endphp
                                        <div class="alert alert-warning mt-3">
                                            <b>Debug dossiers non affichés :</b>
                                            @if(!empty($debugRows))
                                                <ul class="mb-0">
                                                    @foreach($debugRows as $row)
                                                        <li>
                                                            Dossier #{{ $row['id'] }} ({{ $row['numero'] }}) — Statut: {{ $row['statut'] }} — Sinistre: {{ $row['sinistre_id'] ?? 'null' }} — User: {{ $row['sinistre_user_id'] ?? 'null' }} (role={{ $row['sinistre_user_role'] ?? 'null' }}, statut sinistre={{ $row['sinistre_statut'] ?? 'null' }})<br>
                                                            <span class="text-danger">Raison : {{ $row['reason'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-success">Aucun dossier filtré, tous les dossiers valides sont affichés.</span>
                                            @endif
                                        </div>
                                        @forelse($dossiers as $dossier)
                                            <tr>
                                                <td>{{ $dossier->id }}</td>
                                                <td>{{ $dossier->numero }}</td>
                                                <td>
                                                    @if($dossier->statut=='en_attente')
                                                        <span class="badge bg-warning text-dark">En attente</span>
                                                    @elseif($dossier->statut=='en_cours')
                                                        <span class="badge bg-info text-dark">En cours</span>
                                                    @endif
                                                </td>
                                                <td>{{ $dossier->sinistre->titre ?? '-' }}</td>
                                                <td>
                                                    <form method="POST" action="{{ url('api/dossiers/'.$dossier->id.'/valider') }}" style="display:inline-block;">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" onclick="return confirm('Valider ce dossier ?')">Valider</button>
                                                    </form>
                                                    <form method="POST" action="{{ url('api/dossiers/'.$dossier->id.'/refuser') }}" style="display:inline-block; margin-left:5px;">
                                                        @csrf
                                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Refuser ce dossier ?')">Refuser</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="bg-light p-0 border-top-0">
                                                    <div class="px-3 py-2">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <div class="fw-semibold text-secondary mb-1"><i class="bi bi-info-circle me-1"></i>Informations du sinistre</div>
                                                                <div class="small mb-1"><b>Titre :</b> {{ $dossier->sinistre->titre ?? '-' }}</div>
                                                                <div class="small mb-1"><b>Type :</b> {{ $dossier->sinistre->type ?? '-' }}</div>
                                                                <div class="small mb-1"><b>Description :</b> {{ $dossier->sinistre->description ?? '-' }}</div>
                                                                <div class="small mb-1"><b>Date déclaration :</b> {{ $dossier->sinistre->date_declaration ?? '-' }}</div>
                                                                <div class="small mb-1"><b>Documents :</b>
                                                                    @php $docs = $dossier->sinistre->documents ?? collect(); @endphp
                                                                    @if($docs->count())
                                                                        <ul class="mb-1 ps-3">
                                                                            @foreach($docs as $doc)
                                                                                <li><a href="{{ url('storage/'.$doc->chemin) }}" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> {{ $doc->nom }}</a></li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        <span class="text-muted">Aucun document joint</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <div class="fw-semibold text-secondary mb-1"><i class="bi bi-chat-left-text me-1"></i>Commentaires/rapports d'expertise</div>
                                                                <div class="mb-2" style="max-height:120px; overflow-y:auto;">
                                                                    @php $commentaires = $dossier->commentaires()->with('user')->latest()->get(); @endphp
                                                                    @forelse($commentaires as $commentaire)
                                                                        <div class="mb-1 small border-start border-3 ps-2 @if($commentaire->user_id == Auth::id()) border-success @else border-secondary @endif">
                                                                            <span class="fw-bold">{{ $commentaire->user->prenom ?? '' }} {{ $commentaire->user->name ?? '' }}</span>
                                                                            <span class="text-muted">({{ $commentaire->created_at->format('d/m/Y H:i') }})</span> :<br>
                                                                            <span>{{ $commentaire->contenu }}</span>
                                                                        </div>
                                                                    @empty
                                                                        <span class="text-muted">Aucun commentaire pour ce dossier.</span>
                                                                    @endforelse
                                                                </div>
                                                                <form method="POST" action="{{ route('commentaires.store') }}" class="d-flex align-items-center gap-2">
                                                                    @csrf
                                                                    <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                                                                    <input type="text" name="contenu" class="form-control form-control-sm" placeholder="Ajouter un commentaire ou rapport..." required maxlength="500">
                                                                    <button class="btn btn-outline-primary btn-sm" type="submit"><i class="bi bi-send"></i> Publier</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center">Aucun dossier à traiter.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Axios et scripts d'action retirés : retour aux formulaires HTML classiques -->
@endsection

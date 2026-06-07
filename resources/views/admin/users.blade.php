@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header Area -->
    <div class="card mb-4 border-0 shadow-lg">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: rgba(17, 24, 39, 0.45);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-warning" style="font-size:2rem;"></i>
                <div>
                    <h3 class="mb-0 text-white">Gestion des utilisateurs</h3>
                    <p class="mb-0 text-muted small">Consultez, modifiez et validez les comptes utilisateurs de l'application.</p>
                </div>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Nouvel utilisateur
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <!-- Card 1: Existing Users -->
    <div class="card mb-4 border-0">
        <div class="card-header bg-dark bg-opacity-30 d-flex align-items-center gap-2">
            <i class="bi bi-person-workspace text-primary"></i>
            <h5 class="mb-0 text-white">Utilisateurs existants</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><span class="text-muted fw-bold">#{{ $user->id }}</span></td>
                                <td>
                                    <div class="fw-bold text-white">{{ $user->prenom }} {{ $user->name }}</div>
                                </td>
                                <td><span class="text-light">{{ $user->email }}</span></td>
                                <td>
                                    <span class="badge @if($user->role==='admin') bg-danger @elseif($user->role==='expert') bg-success @elseif($user->role==='agent') bg-primary @else bg-info @endif text-white">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge @if($user->status==='active') bg-success @else bg-warning @endif text-white">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td><span class="text-muted">{{ $user->created_at->format('d/m/Y') }}</span></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm py-1.5 px-3">
                                            <i class="bi bi-pencil-fill" style="font-size:0.8rem;"></i> Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm py-1.5 px-3" onclick="return confirm('Supprimer cet utilisateur ?')">
                                                <i class="bi bi-trash-fill" style="font-size:0.8rem;"></i> Supprimer
                                            </button>
                                        </form>
                                        @if($user->status === 'pending')
                                            <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm py-1.5 px-3">
                                                    <i class="bi bi-check-circle" style="font-size:0.8rem;"></i> Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm py-1.5 px-3">
                                                    <i class="bi bi-x-circle" style="font-size:0.8rem;"></i> Refuser
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card 2: Pending Accounts -->
    @if($pendingUsers->count())
        <div class="card border-0">
            <div class="card-header bg-dark bg-opacity-30 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-warning"></i>
                <h5 class="mb-0 text-white">Demandes d'inscription en attente</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle demandé</th>
                                <th>Demandé le</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $pendingUser)
                                <tr>
                                    <td><span class="text-muted fw-bold">#{{ $pendingUser->id }}</span></td>
                                    <td>
                                        <div class="fw-bold text-white">{{ $pendingUser->prenom }} {{ $pendingUser->name }}</div>
                                    </td>
                                    <td><span class="text-light">{{ $pendingUser->email }}</span></td>
                                    <td>
                                        <span class="badge @if($pendingUser->role==='expert') bg-success @elseif($pendingUser->role==='agent') bg-primary @else bg-info @endif text-white">
                                            {{ strtoupper($pendingUser->role) }}
                                        </span>
                                    </td>
                                    <td><span class="text-muted">{{ $pendingUser->created_at->format('d/m/Y') }}</span></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                                            <form method="POST" action="{{ route('admin.users.approve', $pendingUser->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm py-1.5 px-3">
                                                    <i class="bi bi-check-circle" style="font-size:0.8rem;"></i> Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.reject', $pendingUser->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm py-1.5 px-3">
                                                    <i class="bi bi-x-circle" style="font-size:0.8rem;"></i> Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

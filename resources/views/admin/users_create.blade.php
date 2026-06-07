@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h3>Créer un utilisateur</h3>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input id="prenom" name="prenom" type="text" class="form-control" value="{{ old('prenom') }}" required>
            </div>
            <div class="col-md-6">
                <label for="name" class="form-label">Nom</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label for="telephone" class="form-label">Téléphone</label>
                <input id="telephone" name="telephone" type="text" class="form-control" value="{{ old('telephone') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                    <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="expert" {{ old('role') === 'expert' ? 'selected' : '' }}>Expert</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Statut</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" name="password" type="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input id="pays" name="pays" type="text" class="form-control" value="{{ old('pays') }}">
        </div>

        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input id="date_naissance" name="date_naissance" type="date" class="form-control" value="{{ old('date_naissance') }}">
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection

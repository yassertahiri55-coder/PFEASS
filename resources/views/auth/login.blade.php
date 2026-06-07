@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 150px);">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary bg-gradient text-white rounded-top-4 text-center" style="min-height:60px;">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Connexion" width="38" height="38" style="background:#fff;border-radius:50%;padding:3px;box-shadow:0 1px 4px #b0c6f7;">
                <span style="font-size:1.3rem; margin-left:10px;letter-spacing:1px;">Connexion</span>
            </div>
            <div class="card-body bg-light rounded-bottom-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

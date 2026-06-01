@extends('layouts.app')

@section('content')
<style>
    body { background: linear-gradient(120deg, #f8fafc 0%, #e6eefd 100%) !important; font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary bg-gradient text-white rounded-top-4 d-flex align-items-center" style="min-height:60px;">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" width="38" height="38" class="me-2" style="background:#fff;border-radius:50%;padding:3px;box-shadow:0 1px 4px #b0c6f7;">
                    <span style="font-size:1.3rem;letter-spacing:1px;">Espace Administrateur</span>
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    <h4 class="mb-3">Bienvenue, <span class="text-primary">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</span></h4>
                    <p class="mb-4">Vous pouvez <b>consulter tous les dossiers</b>, <b>gérer les utilisateurs</b> et administrer l'application.<br><span class="text-muted small">Interface claire, rapide et moderne.</span></p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm text-center">
                                <i class="bi bi-folder2-open text-info" style="font-size:2rem;"></i>
                                <div class="fw-bold mt-2">Gestion des dossiers</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm text-center">
                                <i class="bi bi-people-fill text-success" style="font-size:2rem;"></i>
                                <div class="fw-bold mt-2">Gestion des utilisateurs</div>
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
@endsection

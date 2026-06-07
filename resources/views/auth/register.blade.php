@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px);">
    <div class="col-md-6 text-center">
        <div class="alert alert-info p-5 rounded-4 shadow-sm">
            <i class="bi bi-info-circle" style="font-size:2.5rem; color: #0284c7;"></i>
            <h3 class="mt-3 font-bold" style="font-family: 'Outfit', sans-serif;">Inscription désactivée</h3>
            <p class="mb-0 text-secondary mt-2">L'inscription se fait uniquement par l'administrateur.<br>Veuillez contacter votre responsable d'agence pour obtenir un accès.</p>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

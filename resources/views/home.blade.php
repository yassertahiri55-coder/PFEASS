@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    @if(isset($notifications) && $notifications->count())
                        <hr>
                        <h5>Notifications récentes</h5>
                        <ul class="list-group mt-3">
                            @foreach($notifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ ucfirst($notification->type ?? 'info') }}</strong>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                    </div>
                                    <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted mt-3">Aucune notification reçue pour le moment.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

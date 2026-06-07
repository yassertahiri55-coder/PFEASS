<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PFEASS') }} - Espace Assurances</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Outfit:300,400,500,600,700,800&family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts via Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback styles in case Vite is not active -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @endif
</head>
<body>
    <!-- Background Decorative Glows -->
    <div class="position-fixed top-0 start-0 rounded-circle bg-primary" style="width: 45vw; height: 45vw; filter: blur(120px); opacity: 0.08; z-index: -1; pointer-events: none;"></div>
    <div class="position-fixed bottom-0 end-0 rounded-circle bg-info" style="width: 40vw; height: 40vw; filter: blur(120px); opacity: 0.07; z-index: -1; pointer-events: none;"></div>

    <div id="app">
        <!-- Navigation Header -->
        <nav class="navbar navbar-expand-md navbar-light navbar-custom sticky-top py-3">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-3 shadow-sm" style="width: 34px; height: 34px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                    <span style="font-weight: 800; font-size: 1.15rem; letter-spacing: -0.3px; color: #ffffff;">
                        PFEASS <span class="text-primary" style="font-weight: 500;">Assur</span>
                    </span>
                </a>

                <!-- Responsive Toggler -->
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links / Actions -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold px-3 text-secondary" style="font-size: 0.9rem;" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-primary px-4 shadow-sm" style="font-size: 0.85rem;" href="{{ route('register') }}">{{ __('S\'inscrire') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- User Dropdown Menu -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2 fw-bold text-white px-3 py-1.5 border border-secondary rounded-pill bg-dark bg-opacity-40 shadow-sm" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="font-size: 0.85rem;">
                                    <div class="bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    {{ Auth::user()->prenom }} {{ Auth::user()->name }}
                                    <span class="badge bg-secondary ms-1 text-white" style="font-size: 0.65rem; background: rgba(255,255,255,0.15) !important;">
                                        {{ strtoupper(Auth::user()->role) }}
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-2 rounded-3" aria-labelledby="navbarDropdown" style="min-width: 200px; background: #111827;">
                                    <a class="dropdown-item py-2 rounded-2 text-secondary d-flex align-items-center gap-2" href="{{ route('home') }}" style="font-size: 0.85rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v4.875h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        Tableau de bord
                                    </a>
                                    
                                    <hr class="my-1 border-light">
                                    
                                    <a class="dropdown-item py-2 rounded-2 text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="font-size: 0.85rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                        </svg>
                                        {{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>
    </div>
    
    <!-- Bootstrap bundle JS CDN as fallback in case local Vite is not running -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

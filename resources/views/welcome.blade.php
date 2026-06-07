<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PFEASS - Plateforme Modernisée de Gestion d'Assurances</title>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css?family=Outfit:300,400,500,600,700,800&family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">
        
        <!-- Styles / Scripts via Vite -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
            .glass-panel {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.4);
            }
            .dark .glass-panel {
                background: rgba(15, 15, 20, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .bg-glow {
                filter: blur(120px);
                opacity: 0.15;
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-12px); }
                100% { transform: translateY(0px); }
            }
        </style>
    </head>
    <body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased min-h-screen relative overflow-x-hidden selection:bg-blue-500 selection:text-white">
        
        <!-- Background Decorative Glows -->
        <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-600 bg-glow -z-10"></div>
        <div class="absolute top-[20%] right-[-10%] w-[45vw] h-[45vw] rounded-full bg-purple-600 bg-glow -z-10"></div>
        <div class="absolute bottom-[10%] left-[20%] w-[40vw] h-[40vw] rounded-full bg-cyan-500 bg-glow -z-10"></div>

        <!-- Sticky Header / Navigation -->
        <header class="sticky top-0 w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="glass-panel rounded-2xl px-6 py-4 flex items-center justify-between shadow-sm">
                    <!-- Logo / Identity -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-zinc-950 to-zinc-700 dark:from-white dark:to-zinc-300 bg-clip-text text-transparent">
                            PFEASS <span class="text-blue-600 font-semibold text-lg">Assur</span>
                        </span>
                    </a>

                    <!-- Nav Links - Desktop -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#services" class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white transition-colors">Services</a>
                        <a href="#features" class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white transition-colors">Avantages</a>
                        <a href="#process" class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white transition-colors">Fonctionnement</a>
                        <a href="#support" class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white transition-colors">Contact</a>
                    </div>

                    <!-- Authenticaton Buttons / CTA -->
                    <div class="flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5">
                                    Mon Espace
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-white px-4 py-2 transition-colors">
                                    Connexion
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 shadow-sm transition-all hover:-translate-y-0.5">
                                        S'inscrire
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-12 pb-20 md:pt-20 md:pb-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Hero Info Text -->
                    <div class="lg:col-span-6 space-y-8 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/50 border border-blue-200/50 dark:border-blue-800/30 text-blue-600 dark:text-blue-400 text-xs font-semibold tracking-wider uppercase">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                            Gestion intelligente des dossiers d'assurance
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-zinc-900 dark:text-white">
                            Simplifiez la gestion de vos <br />
                            <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 bg-clip-text text-transparent">
                                Sinistres & Contrats
                            </span>
                        </h1>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 font-normal leading-relaxed max-w-xl mx-auto lg:mx-0">
                            PFEASS est une plateforme sécurisée conçue pour accélérer le traitement de vos dossiers. Déclarez vos sinistres en ligne, transmettez vos pièces justificatives en un clic, et suivez la validation de nos experts agréés en temps réel.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-xl shadow-blue-500/20 hover:shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                                    Commencer maintenant
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 ml-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @endif
                            <a href="#services" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-semibold border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                                Découvrir nos services
                            </a>
                        </div>

                        <!-- Mini Trust Proofs -->
                        <div class="pt-6 grid grid-cols-3 gap-4 border-t border-zinc-200/60 dark:border-zinc-800/40 max-w-md mx-auto lg:mx-0 text-left">
                            <div>
                                <h4 class="text-2xl font-bold text-zinc-900 dark:text-white">99.8%</h4>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Satisfaction</p>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold text-zinc-900 dark:text-white">-48h</h4>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Traitement moyen</p>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold text-zinc-900 dark:text-white">10K+</h4>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Clients actifs</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Visual Image -->
                    <div class="lg:col-span-6 flex justify-center items-center relative">
                        <!-- Floating graphic card element back -->
                        <div class="absolute -top-6 -left-6 w-72 h-72 rounded-full bg-indigo-500/10 blur-3xl -z-10"></div>
                        <div class="absolute -bottom-8 -right-6 w-60 h-60 rounded-full bg-cyan-500/10 blur-3xl -z-10"></div>
                        
                        <div class="relative w-full max-w-lg lg:max-w-none">
                            <div class="glass-panel p-4 rounded-3xl shadow-2xl relative overflow-hidden transition-all duration-500 hover:scale-[1.01] hover:shadow-blue-500/5 group border border-white/60 dark:border-zinc-800/60">
                                <img 
                                    src="{{ asset('images/insurance_banner.png') }}" 
                                    alt="Illustration assurance habitation et automobile" 
                                    class="w-full h-auto object-cover rounded-2xl animate-float"
                                />
                                
                                <!-- Floating indicator overlay -->
                                <div class="absolute bottom-6 left-6 right-6 glass-panel py-3 px-4 rounded-xl flex items-center justify-between border border-white/80 dark:border-zinc-800/80 shadow-md">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                                        <span class="text-xs font-semibold tracking-wide text-zinc-700 dark:text-zinc-300 uppercase">Protection Active</span>
                                    </div>
                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Assurance Multi-Risques</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Services Grid Section -->
        <section id="services" class="py-20 bg-zinc-100/50 dark:bg-zinc-900/30 border-y border-zinc-200/50 dark:border-zinc-800/30 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white">
                        Des Fonctionnalités Dédiées à votre Tranquillité
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400">
                        Notre plateforme met en relation assurés, agents et experts pour accélérer le traitement de chaque sinistre avec une transparence totale.
                    </p>
                </div>

                <!-- Features Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Card 1 -->
                    <div class="glass-panel p-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3 text-zinc-900 dark:text-white">Dépôt Simplifié</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Renseignez les détails du sinistre en ligne et importez directement vos photos et justificatifs en quelques clics.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="glass-panel p-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3 text-zinc-900 dark:text-white">Suivi en Temps Réel</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Soyez averti à chaque étape de l'avancement de votre dossier, de sa soumission jusqu'au remboursement.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="glass-panel p-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.97 5.97 0 0 0-.75-2.906m-.175-2.311a8.967 8.967 0 0 0-3.06-1.077 4.902 4.902 0 0 0-1.745-.008 8.99 8.99 0 0 0-3.06 1.077m4.903-8.305a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3 text-zinc-900 dark:text-white">Expertise Agréée</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Nos experts évaluent vos pièces justificatives avec impartialité pour vous proposer un remboursement juste.
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="glass-panel p-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-950 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-6 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.958-.659-1.171-.879-1.171-2.303 0-3.182 1.172-.879 3.07-.879 4.242 0L15 8.75m-3-5.25v.75m0 16.5v.75" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3 text-zinc-900 dark:text-white">Remboursements Rapides</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Dès validation du dossier par l'expert, le versement de vos fonds d'indemnisation est immédiatement programmé.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Process / Interactive Journey Section -->
        <section id="process" class="py-20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                    
                    <!-- Left: Claim Validation Image -->
                    <div class="lg:col-span-6 relative">
                        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full bg-cyan-500/10 blur-3xl -z-10"></div>
                        <div class="glass-panel p-4 rounded-3xl shadow-xl border border-white/60 dark:border-zinc-800/60">
                            <img 
                                src="{{ asset('images/insurance_claim.png') }}" 
                                alt="Dossier d'assurance validé par un expert" 
                                class="w-full h-auto object-cover rounded-2xl"
                            />
                        </div>
                    </div>

                    <!-- Right: Process steps -->
                    <div class="lg:col-span-6 space-y-8">
                        <div class="space-y-3">
                            <span class="text-sm font-semibold tracking-wider text-blue-600 uppercase">Comment ça marche ?</span>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white leading-tight">
                                Votre dossier validé en 5 étapes simples
                            </h2>
                        </div>
                        
                        <div class="space-y-6">
                            
                            <!-- Step 1 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">1</div>
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Création du compte</h4>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                        Inscrivez-vous rapidement pour obtenir un espace assuré personnel et sécurisé.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-sm">2</div>
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Déclaration du sinistre</h4>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                        Indiquez la date, les circonstances et le type de sinistre rencontré (auto, habitation).
                                    </p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-sm">3</div>
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Ajout des justificatifs</h4>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                        Téléchargez vos constats, photos de dommages ou factures d'entretien.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-sm">4</div>
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Évaluation de l'expert</h4>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                        L'expert valide la conformité de vos documents et calcule le montant de l'indemnisation.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-sm">5</div>
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Règlement finalisé</h4>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                        L'indemnité approuvée est immédiatement créditée sur votre compte bancaire.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Bottom Banner Section -->
        <section class="py-16 md:py-24 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative rounded-3xl overflow-hidden bg-gradient-to-tr from-blue-700 via-indigo-700 to-indigo-900 px-8 py-12 md:py-20 text-center text-white shadow-2xl">
                    <!-- Overlay decorative effects -->
                    <div class="absolute inset-0 bg-grid-white/[0.03] -z-10"></div>
                    <div class="absolute top-[-50%] left-[-20%] w-[60vw] h-[60vw] rounded-full bg-blue-500/20 blur-3xl -z-10"></div>
                    
                    <div class="max-w-2xl mx-auto space-y-6 relative z-10">
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
                            Prêt à Simplifier la Gestion de vos Assurances ?
                        </h2>
                        <p class="text-indigo-100 text-base md:text-lg leading-relaxed font-light">
                            Rejoignez dès maintenant PFEASS Assur pour gérer l'ensemble de vos sinistres de manière dématérialisée, rapide et entièrement sécurisée.
                        </p>
                        
                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold bg-white text-blue-900 hover:bg-zinc-50 transition-colors shadow-lg">
                                    Créer mon compte
                                </a>
                            @endif
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold bg-indigo-600/50 text-white hover:bg-indigo-600/70 border border-white/20 transition-colors">
                                    Se connecter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Section -->
        <footer id="support" class="bg-zinc-100 dark:bg-zinc-950 border-t border-zinc-200/60 dark:border-zinc-800/60 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                    
                    <!-- Left: Brand -->
                    <div class="space-y-4">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                            </div>
                            <span class="font-extrabold text-lg tracking-tight text-zinc-900 dark:text-white">PFEASS</span>
                        </a>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Votre plateforme de dématérialisation et d'optimisation des dossiers d'assurance. Déclaration, expertise et remboursement accéléré.
                        </p>
                    </div>

                    <!-- Middle 1: Links -->
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Navigation</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#services" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-white transition-colors">Services</a></li>
                            <li><a href="#features" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-white transition-colors">Avantages</a></li>
                            <li><a href="#process" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-white transition-colors">Fonctionnement</a></li>
                        </ul>
                    </div>

                    <!-- Middle 2: Support -->
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Support & Contact</h4>
                        <ul class="space-y-2.5">
                            <li class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                support@pfeass.com
                            </li>
                            <li class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.802-5.127-4.103-6.927-6.927l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                +33 (0) 1 23 45 67 89
                            </li>
                        </ul>
                    </div>

                    <!-- Right: Info -->
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Application PFE</h4>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Projet de Fin d'Études universitaire. Réalisé avec Laravel 13, Tailwind CSS v4 et Vite.
                        </p>
                    </div>

                </div>

                <div class="border-t border-zinc-200/50 dark:border-zinc-800/50 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        &copy; {{ date('Y') }} PFEASS. Tous droits réservés.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-blue-600 transition-colors">Mentions légales</a>
                        <span class="text-zinc-300 dark:text-zinc-800">|</span>
                        <a href="#" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-blue-600 transition-colors">Politique de confidentialité</a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>

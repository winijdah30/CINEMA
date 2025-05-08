@auth
    @extends('template')
    
    @section('title', 'Bienvenue au Nord Pitcha')
    @section('navbar')
    <a href="{{ route('home') }}" class="flex items-center">
        <img src="{{ asset('images/home.jpg') }}" 
             alt="Logo Nord Pitcha" 
             class="h-16">
    </a>
    <ul class="flex space-x-4">
        <li><a class="nav-link fw-bold" href="{{route('clients.cart')}}">
            <i class="bi bi-cart"></i> Panier</a></li>
        <li><a href="{{route('tarif')}}" class="text-gray-300 hover:text-white">Tarifs</a></li>
        <li><a href="{{route('orders.index')}}" class="text-gray-300 hover:text-white">Commandes</a></li>
        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
        <li>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="nav-link btn btn-danger px-3 rounded border-0">
            Déconnexion
            </button>
        </form>
        </li>
    </ul>
    @endsection

    @section('content')
    <div class="container mx-auto mt-4">
        <a href="{{ route('movies.create') }}" class="btn btn-primary mb-4">Ajouter un film</a>
        
        <!-- Premier carrousel - Films -->
        <div class="movie-carousel-container relative mb-12">
            <h2 class="text-xl font-bold text-white mb-4">Nos films à l'affiche</h2>
            
            <div class="movie-carousel flex overflow-x-auto pb-6 scroll-smooth" id="moviesCarousel">
                @php
                    $images = ['film1.jpg', 'film2.jpg', 'film3.jpg', 'film4.jpg'];
                @endphp
                @foreach($movies as $movie)
                <div class="movie-card flex-shrink-0 w-64 mx-2 bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <a href="{{ route('movies.show', $movie->id) }}">
                        <img src="{{ asset('images/' . $images[array_rand($images)]) }}" 
                            class="w-full h-48 object-cover" 
                            alt="{{ $movie->name }}">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white truncate">{{ $movie->name }}</h3>
                        <div class="flex mt-2">
                            @php 
                                $hours = floor($movie->duration / 60);
                                $minutes = $movie->duration % 60;
                            @endphp
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-2">
                                {{ $hours }}h{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs">
                                {{ $movie->version }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="carousel-nav left-0" onclick="scrollCarousel('moviesCarousel', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-nav right-0" onclick="scrollCarousel('moviesCarousel', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <!-- Deuxième carrousel - Animes -->
        <div class="movie-carousel-container relative">
            <h2 class="text-xl font-bold text-white mb-4">Nos derniers épisodes</h2>
            
            <div class="movie-carousel flex overflow-x-auto pb-6 scroll-smooth" id="animesCarousel">
                @php
                    $images = ['film1.jpg', 'film2.jpg', 'film3.jpg', 'film4.jpg'];
                @endphp
                @foreach($animes as $anime)
                <div class="movie-card flex-shrink-0 w-64 mx-2 bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <a href="{{ route('animes.show', $anime->id) }}">
                        <img src="{{ asset('images/' . $images[array_rand($images)]) }}" 
                            class="w-full h-48 object-cover" 
                            alt="{{ $anime->name }}">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white truncate">{{ $anime->name }}</h3>
                        <div class="flex mt-2">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-2">
                               {{$anime->duration}}min
                            </span>
                            <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs">
                                vo
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="carousel-nav left-0" onclick="scrollCarousel('animesCarousel', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-nav right-0" onclick="scrollCarousel('animesCarousel', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
    @endsection

    @push('scripts')
<script>
    // Configuration
    const SCROLL_INTERVAL = 3000; // 3 secondes entre chaque défilement
    const SCROLL_AMOUNT = 0.5; // Défile 50% de la largeur visible
    
    // Fonction de défilement
    function scrollCarousel(carouselId, direction) {
        const carousel = document.getElementById(carouselId);
        const scrollWidth = carousel.offsetWidth * SCROLL_AMOUNT;
        carousel.scrollBy({
            left: direction * scrollWidth,
            behavior: 'smooth'
        });
        
        // Réinitialise le timer après une action manuelle
        resetAutoScroll(carouselId);
    }
    
    // Défilement automatique
    function startAutoScroll(carouselId) {
        const carousel = document.getElementById(carouselId);
        let autoScrollInterval = setInterval(() => {
            // Vérifie si on est arrivé à la fin
            if (carousel.scrollLeft + carousel.offsetWidth >= carousel.scrollWidth - 10) {
                // Retour au début
                carousel.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
            } else {
                // Défilement normal
                carousel.scrollBy({
                    left: carousel.offsetWidth * SCROLL_AMOUNT,
                    behavior: 'smooth'
                });
            }
        }, SCROLL_INTERVAL);
        
        // Stocke l'interval pour pouvoir le clear plus tard
        carousel.dataset.intervalId = autoScrollInterval;
    }
    
    // Réinitialisation du défilement auto
    function resetAutoScroll(carouselId) {
        const carousel = document.getElementById(carouselId);
        clearInterval(parseInt(carousel.dataset.intervalId));
        startAutoScroll(carouselId);
    }
    
    // Navigation clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            scrollCarousel('moviesCarousel', -1);
            scrollCarousel('animesCarousel', -1);
        }
        if (e.key === 'ArrowRight') {
            scrollCarousel('moviesCarousel', 1);
            scrollCarousel('animesCarousel', 1);
        }
    });
    
    // Gestion du survol pour les deux carrousels
    ['moviesCarousel', 'animesCarousel'].forEach(carouselId => {
        const carousel = document.getElementById(carouselId);
        
        carousel.addEventListener('mouseenter', () => {
            clearInterval(parseInt(carousel.dataset.intervalId));
        });
        
        carousel.addEventListener('mouseleave', () => {
            startAutoScroll(carouselId);
        });
    });
    
    // Démarrer au chargement
    document.addEventListener('DOMContentLoaded', () => {
        startAutoScroll('moviesCarousel');
        startAutoScroll('animesCarousel');
        
        // Réinitialiser quand la fenêtre est resize
        window.addEventListener('resize', () => {
            resetAutoScroll('moviesCarousel');
            resetAutoScroll('animesCarousel');
        });
    });
</script>
@endpush
@else
    <div class="text-center d-flex align-items-center justify-content-center vh-100" 
         style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/home.jpg') }}'); 
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                color: white;">
        <div class="container px-4">
            <div class="bg-dark bg-opacity-75 rounded-3 p-5 shadow-lg">
                <h1 class="display-4 fw-bold mb-4">Bienvenue</h1>
                <h3 class="mb-4">Nord Pitcha, au cinéma comme à la maison</h3>
                <p class="lead mb-5">Accédez à votre espace personnel en toute simplicité</p>
                
                <div class="d-flex justify-content-center gap-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                    </a>
                    
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="bi bi-person-plus me-2"></i>Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>
@endauth
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Tournament Arena') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen bg-gradient-to-b from-gray-100 to-gray-200">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <h1 class="text-3xl font-extrabold text-blue-700 hover:text-blue-800 transition duration-300">Tournament Arena</h1>
                            </div>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                            @if (Route::has('login'))
                                <div>
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="font-medium text-gray-800 hover:text-blue-700">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="font-medium text-gray-800 hover:text-blue-700 border border-gray-300 rounded-md px-3 py-1">Log in</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="ml-4 font-medium text-gray-800 hover:text-blue-700 border border-gray-300 rounded-md px-3 py-1">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>


            <!-- Hero Section -->
            <div class="py-16 sm:py-24 bg-gradient-to-r from-blue-50 to-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl">
                        <span class="block text-blue-700">¡Bienvenido a Tournament Arena!</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 sm:mt-6 sm:text-xl lg:text-lg xl:text-xl leading-relaxed">
                        La plataforma ideal para gestionar y participar en torneos deportivos de manera sencilla y eficiente.
                    </p>
                    <div class=" flex mt-12 relative lg:mx-0 lg:col-span-6 lg:flex lg:items-center lg:justify-center">        
                        <img src="logo.png" alt="Tournament Arena" style="width: 300px;">
                    </div>
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-black bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Comenzar ahora
                        </a>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                        <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                            <h1>
                                <span class="block text-sm font-semibold uppercase tracking-wide text-gray-500">Nueva plataforma</span>
                                <span class="mt-1 block text-4xl tracking-tight font-extrabold sm:text-5xl xl:text-6xl">
                                    <span class="block text-gray-900">Gestiona tus torneos</span>
                                    <span class="block text-blue-700">de forma sencilla</span>
                                </span>
                            </h1>
                            <p class="mt-4 text-lg text-gray-600 sm:mt-6 sm:text-xl lg:text-lg xl:text-xl leading-relaxed">
                                La plataforma más completa para organizar, gestionar y participar en torneos deportivos. Ideal para organizadores y equipos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-12 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-blue-700 font-semibold tracking-wide uppercase">Características</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Todo lo que necesitas para gestionar torneos
                        </p>
                    </div>

                    <div class="mt-10">
                        <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                            <div class="relative">
                                <dt>
                                    <div class=" flex items-center justify-start h-12 w-12 rounded-md bg-blue-700 text-black mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Para organizadores</p>
                                </dt>
                                <dd class="mt-2 ml-16 text-base text-gray-500">
                                    Crea y gestiona torneos fácilmente. Administra inscripciones, genera calendarios y mantén actualizadas las clasificaciones.
                                </dd>
                            </div>

                            <div class="relative">
                                <dt>
                                    <div class=" flex items-center justify-start h-12 w-12 rounded-md bg-blue-700 text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Para equipos</p>
                                </dt>
                                <dd class="mt-2 ml-16 text-base text-gray-500">
                                    Inscribe tu equipo en torneos, gestiona tu calendario de partidos y sigue tus clasificaciones en tiempo real.
                                </dd>
                            </div>

                            <div class="relative">
                                <dt>
                                    <div class=" flex items-center justify-start h-12 w-12 rounded-md bg-blue-700 text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Estadísticas avanzadas</p>
                                </dt>
                                <dd class="mt-2 ml-16 text-base text-gray-500">
                                    Accede a estadísticas detalladas de equipos y jugadores. Analiza el rendimiento y comparte resultados con facilidad. (Próximamente)
                                </dd>
                            </div>

                            <div class="relative">
                                <dt>
                                    <div class=" flex items-center justify-start h-12 w-12 rounded-md bg-blue-700 text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Programación automática</p>
                                </dt>
                                <dd class="mt-2 ml-16 text-base text-gray-500">
                                    Nuestro sistema genera calendarios de partidos optimizados para cualquier formato de torneo.
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-blue-700">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                        <span class="block text-black">¿Listo para comenzar?</span>
                        <span class="block text-gray-500">Regístrate ahora y organiza tu primer torneo.</span>
                    </h2>
                    <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0 space-x-4">
                        <div class="inline-flex rounded-md shadow">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                    Registrarse
                                </a>
                            @endif
                        </div>
                        <div class="ml-3 inline-flex rounded-md shadow">
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-black bg-blue-800 hover:bg-blue-900">
                                    Iniciar sesión
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Public Tournaments Section -->
            <div class="bg-gray-100">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                    <div class="lg:w-0 lg:flex-1">
                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            <span class="block">Explora torneos públicos</span>
                            <span class="block text-blue-600 mt-1">Encuentra y únete a competiciones en curso</span>
                        </h2>
                        <p class="mt-3 max-w-3xl text-lg text-gray-500">
                            Descubre torneos abiertos organizados por nuestra comunidad. Mantente al día con los resultados y clasificaciones de las competiciones activas.
                        </p>
                    </div>
                    <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                        <div class="inline-flex rounded-md shadow">
                            <a href="{{ url('/tournaments') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-black bg-blue-700 hover:bg-blue-800">
                                Ver torneos públicos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-50 border-t border-gray-200">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                    <div class="flex justify-center space-x-6 md:order-2">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="black" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="black" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-8 md:mt-0 md:order-1">
                        <p class="text-center text-sm text-gray-500 hover:text-gray-700 transition duration-300">
                            &copy; 2025 Tournament Arena. Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
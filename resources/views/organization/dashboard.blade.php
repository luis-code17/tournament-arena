<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Organización') }}
        </h2>
    </x-slot>

    <style>
        .dashboard-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .dashboard-section:last-child {
            border-bottom: none;
        }
        .section-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .tournament-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: box-shadow 0.3s ease;
        }
        .tournament-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .create-card {
            background-color: #ecfdf5;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }
        .create-link {
            color: #047857;
            font-size: 0.875rem;
        }
        .create-link:hover {
            text-decoration: underline;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-finished {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .detail-link {
            font-size: 0.75rem;
            color: #2563eb;
        }
        .detail-link:hover {
            text-decoration: underline;
        }
        .manage-card {
            background-color: #eff6ff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }
        .manage-link {
            color: #1d4ed8;
            font-size: 0.875rem;
        }
        .manage-link:hover {
            text-decoration: underline;
        }
        .requests-card {
            background-color: #f5f3ff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }
        .requests-link {
            color: #6d28d9;
            font-size: 0.875rem;
        }
        .requests-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Bienvenido, {{ Auth::user()->name }}</h3>
                    
                    <div class="flex flex-col md:flex-row gap-4 mb-6">
                        <!-- Crear Nuevo Torneo -->
                        <div class="create-card md:w-1/3">
                            <h4 class="font-semibold text-emerald-700 mb-2">Crear Nuevo Torneo</h4>
                            <p class="text-sm mb-3">Inicia un nuevo torneo para tu organización.</p>
                            <a href="{{ route('organization.tournaments.create') }}" class="create-link">Crear torneo →</a>
                        </div>
                        
                        <!-- Gestión de Torneos -->
                        <div class="manage-card md:w-1/3">
                            <h4 class="font-semibold text-blue-700 mb-2">Gestión de Torneos</h4>
                            <p class="text-sm mb-3">Administra todos tus torneos en un solo lugar.</p>
                            <a href="{{ route('organization.tournaments.index') }}" class="manage-link">Gestionar torneos →</a>
                        </div>
                        
                        <!-- Solicitudes de Equipos -->
                        <div class="requests-card md:w-1/3">
                            <h4 class="font-semibold text-purple-700 mb-2">Solicitudes de Equipos</h4>
                            <p class="text-sm mb-3">Revisa y gestiona las solicitudes de equipos para tus torneos.</p>
                            <a href="{{ route('organization.requests.index') }}" class="requests-link">Ver solicitudes 
                                @php
                                    $pendingCount = App\Models\TeamTournament::whereIn('tournament_id', 
                                        App\Models\Tournament::where('organization_id', Auth::id())->pluck('id'))
                                        ->where('state', 'pending')
                                        ->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="inline-flex items-center justify-center bg-red-100 text-red-800 text-xs font-semibold ml-1 px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                                @endif
                                →
                            </a>
                        </div>
                    </div>
                    
                    <!-- Torneos Activos -->
                    <div class="dashboard-section">
                        <h4 class="section-header">Torneos Activos</h4>
                        @php
                            $activeTournaments = App\Models\Tournament::where('organization_id', Auth::id())
                                ->where('state', 'in_progress')
                                ->latest()
                                ->take(3)
                                ->get();
                        @endphp

                        @if($activeTournaments->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($activeTournaments as $tournament)
                                    <div class="tournament-card">
                                        <h5 class="font-semibold">{{ $tournament->name }}</h5>
                                        <p class="text-sm text-gray-600 mt-1">Equipos: {{ $tournament->acceptedTeams->count() }}</p>
                                        <div class="flex justify-between items-center mt-3">
                                            <span class="status-badge status-active">En progreso</span>
                                            <a href="{{ route('organization.tournaments.show', $tournament) }}" class="detail-link">Ver detalles →</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(App\Models\Tournament::where('organization_id', Auth::id())->where('state', 'in_progress')->count() > 3)
                                <div class="text-right mt-3">
                                    <a href="{{ route('organization.tournaments.index') }}?state=in_progress" class="text-sm text-blue-600 hover:underline">Ver todos los torneos activos →</a>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-600">No hay torneos activos actualmente.</p>
                        @endif
                    </div>

                    <!-- Solicitudes Pendientes -->
                    <div class="dashboard-section">
                        <h4 class="section-header">Solicitudes Pendientes</h4>
                        @php
                            $pendingRequests = App\Models\TeamTournament::whereIn('tournament_id', 
                                App\Models\Tournament::where('organization_id', Auth::id())->pluck('id'))
                                ->where('state', 'pending')
                                ->with(['team', 'tournament'])
                                ->latest()
                                ->take(3)
                                ->get();
                        @endphp

                        @if($pendingRequests->count() > 0)
                            <div class="space-y-3">
                                @foreach($pendingRequests as $request)
                                    <div class="request-card border border-gray-200 rounded-lg p-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-sm">{{ $request->team->name }}</p>
                                                <p class="text-xs text-gray-500">Solicitud para: {{ $request->tournament->name }}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('organization.requests.index', ['tournament_id' => $request->tournament_id]) }}" class="text-xs text-purple-600 hover:underline">Ver detalles</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($pendingCount > 3)
                                <div class="text-right mt-3">
                                    <a href="{{ route('organization.requests.index') }}" class="text-sm text-purple-600 hover:underline">Ver todas las solicitudes →</a>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-600">No hay solicitudes pendientes actualmente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
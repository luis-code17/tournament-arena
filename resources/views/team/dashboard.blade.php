<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title">
            {{ __('Panel de Equipo') }}
        </h2>
    </x-slot>

    <div class="main-container">
        <div class="content-container">
            @if(session('success'))
                <div class="alert success" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert error" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <div class="card">
                <div class="card-content">
                    <h3 class="welcome-title">Bienvenido, {{ Auth::user()->name }}</h3>
                    
                    <div class="card-grid">
                        <!-- Mis Torneos -->
                        <div class="info-card green">
                            <h4 class="card-title">Mis Torneos</h4>
                            <p class="card-text">Revisa los torneos en los que estás participando.</p>
                            <a href="{{ route('team.tournaments.index') }}" class="card-link">
                                Ver mis torneos
                                <svg class="arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Próximos Partidos -->
                        <div class="info-card purple">
                            <h4 class="card-title">Próximos Partidos</h4>
                            <p class="card-text">Consulta tu calendario de partidos programados.</p>
                            <a href="#" class="card-link">
                                Ver calendario
                                <svg class="arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Resumen de Actividad Reciente -->
                    <div class="section">
                        <h4 class="section-title">Actividad Reciente</h4>
                        
                        @php
                            $pendingRegistrations = App\Models\TeamTournament::where('user_id', Auth::id())
                                ->where('state', 'pending')
                                ->with('tournament')
                                ->latest()
                                ->take(3)
                                ->get();
                                
                            $upcomingMatches = collect(); // Replace with actual matches query when implemented
                        @endphp
                        
                        <div class="activity-container">
                            @if($pendingRegistrations->count() > 0)
                                <div class="activity-card yellow">
                                    <h5 class="activity-title">Solicitudes Pendientes</h5>
                                    <ul class="activity-list">
                                        @foreach($pendingRegistrations as $registration)
                                            <li class="activity-item">
                                                <span>Solicitud para: {{ $registration->tournament->name }}</span>
                                                <span class="date">{{ $registration->created_at->format('d/m/Y') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="view-all">
                                        <a href="{{ route('team.tournaments.index', ['filter' => 'pending']) }}" class="small-link">Ver todas</a>
                                    </div>
                                </div>
                            @elseif($upcomingMatches->count() > 0)
                                <div class="activity-card indigo">
                                    <h5 class="activity-title">Próximos Partidos</h5>
                                    <!-- Upcoming matches content -->
                                </div>
                            @else
                                <p class="no-activity">No hay actividad reciente para mostrar.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Torneos -->
                    <div class="section">
                        <h4 class="section-title">Torneos</h4>
                        
                        @php
                            $tournaments = App\Models\Tournament::where('state', 'pending')
                                ->latest()
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @if($tournaments->count() > 0)
                            <div class="tournament-grid">
                                @foreach($tournaments as $tournament)
                                    <div class="tournament-card">
                                        <div class="tournament-header">
                                            <h5 class="tournament-title">{{ $tournament->name }}</h5>
                                        </div>
                                        <div class="tournament-body">
                                            <p class="tournament-desc">{{ Str::limit($tournament->desc, 80) }}</p>
                                            <div class="tournament-footer">
                                                <span class="tournament-date">{{ $tournament->start_date->format('d/m/Y') }}</span>
                                                <form method="POST" action="{{ route('team.tournaments.register', $tournament) }}" class="inline-form">
                                                    @csrf
                                                    <button type="submit" class="join-link">Unirse →</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="view-all">
                                <a href="{{ route('team.tournaments.index') }}" class="view-all-link">Ver todos los torneos →</a>
                            </div>
                        @else
                            <p class="no-activity">No hay torneos para mostrar.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos generales */
        .main-container {
            padding: 48px 0;
        }
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .header-title {
            font-weight: 600;
            font-size: 20px;
            color: #1f2937;
        }
        
        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 16px;
        }
        .success {
            background-color: #d1fae5;
            border: 1px solid #34d399;
            color: #065f46;
        }
        .error {
            background-color: #fee2e2;
            border: 1px solid #f87171;
            color: #b91c1c;
        }
        
        /* Tarjeta principal */
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-content {
            padding: 24px;
        }
        
        /* Título de bienvenida */
        .welcome-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 16px;
            color: #111827;
        }
        
        /* Grid de tarjetas informativas */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 16px;
        }
        
        @media (min-width: 768px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Tarjetas informativas */
        .info-card {
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        
        .info-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .blue {
            background-color: #eff6ff;
        }
        
        .green {
            background-color: #ecfdf5;
        }
        
        .purple {
            background-color: #f5f3ff;
        }
        
        .yellow {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
        }
        
        .indigo {
            background-color: #eef2ff;
            border: 1px solid #e0e7ff;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .blue .card-title {
            color: #1d4ed8;
        }
        
        .green .card-title {
            color: #047857;
        }
        
        .purple .card-title {
            color: #7e22ce;
        }
        
        .card-text {
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .card-link {
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }
        
        .blue .card-link {
            color: #1d4ed8;
        }
        
        .green .card-link {
            color: #047857;
        }
        
        .purple .card-link {
            color: #7e22ce;
        }
        
        .card-link:hover {
            text-decoration: underline;
        }
        
        .arrow-icon {
            width: 16px;
            height: 16px;
            margin-left: 4px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }
        
        /* Secciones */
        .section {
            margin-top: 32px;
        }
        
        .section-title {
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }
        
        /* Actividad */
        .activity-container {
            margin-top: 16px;
        }
        
        .activity-card {
            padding: 16px;
            border-radius: 8px;
        }
        
        .activity-title {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .yellow .activity-title {
            color: #92400e;
        }
        
        .indigo .activity-title {
            color: #4338ca;
        }
        
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
            margin-top: 8px;
        }
        
        .activity-item {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .date {
            font-size: 12px;
            color: #6b7280;
        }
        
        .view-all {
            margin-top: 12px;
            text-align: right;
        }
        
        .small-link {
            font-size: 12px;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }
        
        .yellow .small-link {
            color: #92400e;
        }
        
        .small-link:hover {
            text-decoration: underline;
        }
        
        .no-activity {
            font-size: 14px;
            color: #6b7280;
        }
        
        /* Torneos */
        .tournament-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 16px;
        }
        
        @media (min-width: 768px) {
            .tournament-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .tournament-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s;
        }
        
        .tournament-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .tournament-header {
            background-color: #f9fafb;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .tournament-title {
            font-weight: 500;
            color: #111827;
        }
        
        .tournament-body {
            padding: 12px;
        }
        
        .tournament-desc {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 12px;
        }
        
        .tournament-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .tournament-date {
            font-size: 12px;
            color: #6b7280;
        }
        
        .join-link {
            font-size: 12px;
            color: #4f46e5;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }
        
        .join-link:hover {
            text-decoration: underline;
        }
        
        .view-all-link {
            font-size: 14px;
            color: #4f46e5;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }
        
        .view-all-link:hover {
            text-decoration: underline;
        }
        /* Estilo para que el botón de tipo formulario se vea como un enlace */
        .inline-form {
            display: inline;
        }

        .inline-form button {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
        }
    </style>
</x-app-layout>
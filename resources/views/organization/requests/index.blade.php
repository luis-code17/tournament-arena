<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Solicitudes de Equipos') }}
            </h2>
            <a href="{{ route('organization.tournaments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver a Torneos') }}
            </a>
        </div>
    </x-slot>

    <style>
        .request-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: box-shadow 0.3s ease;
            margin-bottom: 1rem;
        }
        .request-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .tournament-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: #374151;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
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
        .status-accepted {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .action-button {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .accept-button {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .accept-button:hover {
            background-color: #d1fae5;
        }
        .reject-button {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .reject-button:hover {
            background-color: #fee2e2;
        }
        .team-info {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .team-logo {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            object-fit: cover;
            margin-right: 0.75rem;
            background-color: #f3f4f6;
        }
        .team-name {
            font-weight: 500;
            color: #111827;
        }
        .team-members {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .request-date {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .no-requests {
            text-align: center;
            padding: 2rem 0;
            color: #6b7280;
        }
        .empty-state-icon {
            width: 48px;
            height: 48px;
            display: block;
            margin: 0 auto;
            color: #9ca3af;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }
        .tab {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: #6b7280;
        }
        .tab.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        .tab:hover:not(.active) {
            color: #4b5563;
        }
        .request-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            margin-left: 0.5rem;
        }
        .pending-count {
            background-color: #fef3c7;
            color: #92400e;
        }
        .pagination-container {
            margin-top: 1.5rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="tabs">
                        <a href="{{ route('organization.requests.index', ['filter' => 'pending']) }}" class="tab {{ request('filter', 'pending') === 'pending' ? 'active' : '' }}">
                            Pendientes 
                            @if($pendingCount > 0)
                                <span class="request-count pending-count">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('organization.requests.index', ['filter' => 'accepted']) }}" class="tab {{ request('filter') === 'accepted' ? 'active' : '' }}">
                            Aceptadas
                        </a>
                        <a href="{{ route('organization.requests.index', ['filter' => 'rejected']) }}" class="tab {{ request('filter') === 'rejected' ? 'active' : '' }}">
                            Rechazadas
                        </a>
                    </div>

                    @if ($requests->count() > 0)
                        @php
                            $currentTournament = null;
                        @endphp

                        @foreach ($requests as $request)
                            @if ($currentTournament !== $request->tournament_id)
                                @if ($currentTournament !== null)
                                    </div>
                                @endif
                                
                                @php
                                    $currentTournament = $request->tournament_id;
                                @endphp

                                <div class="tournament-section mb-6">
                                    <h3 class="tournament-title">
                                        {{ $request->tournament->name }}
                                    </h3>
                            @endif

                            <div class="request-card">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="team-info">
                                            @if ($request->team->logo)
                                                <img src="{{ asset('storage/' . $request->team->logo) }}" alt="{{ $request->team->name }}" class="team-logo">
                                            @else
                                                <div class="team-logo flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="team-name">{{ $request->team->name }}</div>
                                                <div class="team-members">{{ optional($request->team)->members_count ?? 0 }} miembros</div>
                                            </div>
                                        </div>
                                        <div class="request-date">Solicitud: {{ $request->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        @if ($request->state === 'pending')
                                            <span class="status-badge status-pending mr-3">Pendiente</span>
                                            <form action="{{ route('organization.requests.accept', $request) }}" method="POST" class="mr-2">
                                                @csrf
                                                <button type="submit" class="action-button accept-button">Aceptar</button>
                                            </form>
                                            <form action="{{ route('organization.requests.reject', $request) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="action-button reject-button">Rechazar</button>
                                            </form>
                                        @elseif ($request->state === 'accepted')
                                            <span class="status-badge status-accepted">Aceptada</span>
                                        @elseif ($request->state === 'rejected')
                                            <span class="status-badge status-rejected">Rechazada</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($currentTournament !== null)
                            </div>
                        @endif

                        <div class="pagination-container">
                            {{ $requests->appends(['filter' => request('filter', 'pending')])->links() }}
                        </div>
                    @else
                        <div class="no-requests">
                            <svg xmlns="http://www.w3.org/2000/svg" class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            
                            @if (request('filter', 'pending') === 'pending')
                                <p class="mt-2">No hay solicitudes pendientes.</p>
                            @elseif (request('filter') === 'accepted')
                                <p class="mt-2">No hay solicitudes aceptadas.</p>
                            @elseif (request('filter') === 'rejected')
                                <p class="mt-2">No hay solicitudes rechazadas.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
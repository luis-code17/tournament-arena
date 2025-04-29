<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Torneos') }}
            </h2>
            <a href="{{ route('organization.tournaments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Crear Torneo') }}
            </a>
        </div>
    </x-slot>

    <style>
        .tournament-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: box-shadow 0.3s ease;
        }
        .tournament-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
            display: inline-block;
            position: relative;
            margin-right: 0;
        }
        .detail-link:hover {
            text-decoration: underline;
        }
        .detail-link:not(:last-child)::after {
            content: '|';
            color: #d1d5db;
            margin: 0 8px;
            display: inline-block;
        }
        .action-links {
            display: flex;
            align-items: center;
        }
        button.detail-link {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-family: inherit;
        }
        .text-red-600 {
            color: #dc2626;
        }
        .pagination-container {
            margin-top: 1.5rem;
        }
        .empty-state-icon {
            height: 3rem !important; 
            width: 3rem !important;
            color: #9ca3af;
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
                    @if ($tournaments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($tournaments as $tournament)
                                <div class="tournament-card">
                                    <h5 class="font-semibold">{{ $tournament->name }}</h5>
                                    <div class="text-sm text-gray-500 mt-2">
                                        {{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-2">
                                        {{ $tournament->accepted_teams_count ?? $tournament->acceptedTeams->count() }} equipos
                                    </div>
                                    <div class="flex justify-between items-center mt-3">
                                        @if ($tournament->state == 'pending')
                                            <span class="status-badge status-pending">Pendiente</span>
                                        @elseif ($tournament->state == 'in_progress')
                                            <span class="status-badge status-active">En progreso</span>
                                        @else
                                            <span class="status-badge status-finished">Finalizado</span>
                                        @endif
                                        <div class="action-links">
                                            <a href="{{ route('organization.tournaments.show', $tournament) }}" class="detail-link">Ver</a>
                                            <a href="{{ route('organization.tournaments.edit', $tournament) }}" class="detail-link">Editar</a>
                                            <form class="inline" action="{{ route('organization.tournaments.destroy', $tournament) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este torneo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="detail-link text-red-600">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="pagination-container">
                            {{ $tournaments->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="empty-state-icon mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="mt-2 text-gray-500">No hay torneos registrados. ¡Crea tu primer torneo!</p>
                            <a href="{{ route('organization.tournaments.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text- uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Crear Torneo') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
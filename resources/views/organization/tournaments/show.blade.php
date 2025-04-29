<x-app-layout>
    <style>
        .section-header {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .detail-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
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
        .action-button {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .section-divider {
            margin: 2rem 0;
            border-top: 1px solid #e5e7eb;
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tournament->name }}
            </h2>
            <div>
                <a href="{{ route('organization.tournaments.edit', $tournament) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('organization.tournaments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Volver') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes de sesión -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Tournament Details -->
                    <div class="mb-8">
                        <h3 class="section-header">Detalles del Torneo</h3>
                        <div class="detail-grid">
                            <div>
                                <p class="detail-label">Organizado por:</p>
                                <p class="detail-value">{{ $tournament->organization->name }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Estado:</p>
                                <p class="detail-value">
                                    @if($tournament->isPending())
                                        <span class="status-badge status-pending">Pendiente</span>
                                    @elseif($tournament->isInProgress())
                                        <span class="status-badge status-active">En Progreso</span>
                                    @else
                                        <span class="status-badge status-finished">Finalizado</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="detail-label">Fecha de Inicio:</p>
                                <p class="detail-value">{{ $tournament->start_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Fecha de Finalización:</p>
                                <p class="detail-value">{{ $tournament->end_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <p class="detail-label">Descripción:</p>
                            <p class="mt-1">{{ $tournament->desc }}</p>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Actions -->
                    <div class="mb-8">
                        <h3 class="section-header">Acciones</h3>
                        
                        @if($tournament->isPending() && Auth::id() === $tournament->organization_id)
                            <form action="{{ route('organization.tournaments.start', $tournament) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="action-button inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Iniciar Torneo') }}
                                </button>
                            </form>
                        @endif

                        @if($tournament->isInProgress() && Auth::id() === $tournament->organization_id)
                            <form action="{{ route('organization.tournaments.finish', $tournament) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="action-button inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Finalizar Torneo') }}
                                </button>
                            </form>
                        @endif
                        
                        @if($tournament->isPending() && Auth::id() === $tournament->organization_id)
                            <form action="{{ route('organization.tournaments.destroy', $tournament) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro que deseas eliminar este torneo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('Eliminar Torneo') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="section-divider"></div>

                    <!-- Participants -->
                    <div class="mt-8">
                        <h3 class="section-header">Equipos Participantes</h3>
                        @if($tournament->acceptedTeams->count() > 0)
                            <div class="overflow-x-auto bg-white rounded-lg shadow">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr class="bg-gray-100 border-b">
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($tournament->acceptedTeams as $team)
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-4 px-6">{{ $team->name }}</td>
                                                <td class="py-4 px-6">{{ $team->pivot->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-6 rounded-lg text-center border border-gray-200">
                                <p class="text-gray-500">No hay equipos participantes en este torneo.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
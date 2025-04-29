<x-app-layout>
    <style>
        .form-section {
            margin-bottom: 2rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-input {
            display: block;
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        .form-input:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
        .form-error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .required-mark {
            color: #ef4444;
            margin-left: 0.25rem;
        }
        .state-info {
            padding: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            background-color: #f3f4f6;
        }
        .state-info .state-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .state-pending {
            color: #92400e;
        }
        .state-active {
            color: #065f46;
        }
        .state-finished {
            color: #4b5563;
        }
        .state-note {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Torneo') }}
            </h2>
            <a href="{{ route('organization.tournaments.show', $tournament) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver') }}
            </a>
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
                    <form method="POST" action="{{ route('organization.tournaments.update', $tournament) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-section">
                            <label for="name" class="form-label">{{ __('Nombre del Torneo') }} <span class="required-mark">*</span></label>
                            <input id="name" class="form-input @error('name') border-red-500 @enderror" type="text" name="name" value="{{ old('name', $tournament->name) }}" required autofocus />
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-section">
                            <label for="desc" class="form-label">{{ __('Descripción') }}</label>
                            <textarea id="desc" name="desc" rows="4" class="form-input @error('desc') border-red-500 @enderror">{{ old('desc', $tournament->desc) }}</textarea>
                            @error('desc')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-grid">
                            <div class="form-section">
                                <label for="start_date" class="form-label">{{ __('Fecha de Inicio') }} <span class="required-mark">*</span></label>
                                <input id="start_date" class="form-input @error('start_date') border-red-500 @enderror" type="date" name="start_date" value="{{ old('start_date', $tournament->start_date->format('Y-m-d')) }}" {{ $tournament->isInProgress() || $tournament->isFinished() ? 'disabled' : 'required' }} />
                                @if($tournament->isInProgress() || $tournament->isFinished())
                                    <p class="text-xs text-gray-500 mt-1">La fecha de inicio no puede ser modificada una vez iniciado el torneo.</p>
                                @endif
                                @error('start_date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-section">
                                <label for="end_date" class="form-label">{{ __('Fecha de Finalización') }} <span class="required-mark">*</span></label>
                                <input id="end_date" class="form-input @error('end_date') border-red-500 @enderror" type="date" name="end_date" value="{{ old('end_date', $tournament->end_date->format('Y-m-d')) }}" required />
                                @error('end_date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @if($tournament->isPending())
                            <div class="form-section">
                                <label for="state" class="form-label">{{ __('Estado') }}</label>
                                <select id="state" name="state" class="form-input @error('state') border-red-500 @enderror">
                                    <option value="pending" {{ $tournament->state == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                                @error('state')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="state" value="{{ $tournament->state }}">
                            <div class="form-section">
                                <label class="form-label">{{ __('Estado') }}</label>
                                <div class="state-info">
                                    @if($tournament->isInProgress())
                                        <span class="state-label state-active">En Progreso</span>
                                    @elseif($tournament->isFinished())
                                        <span class="state-label state-finished">Finalizado</span>
                                    @endif
                                    <p class="state-note">El estado no puede ser modificado directamente desde el formulario. Usa los botones específicos para iniciar o finalizar el torneo.</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('organization.tournaments.show', $tournament) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Actualizar Torneo') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configurar fechas mínimas para que end_date sea al menos igual a start_date
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            if (startDateInput && !startDateInput.disabled) {
                // Si la fecha de inicio no está deshabilitada (torneo pendiente)
                startDateInput.addEventListener('change', function() {
                    // Actualizar la fecha mínima de finalización cuando cambie la fecha de inicio
                    endDateInput.setAttribute('min', this.value);
                    
                    // Si la fecha de finalización es anterior a la de inicio, actualizarla
                    if (endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                });
                
                // Trigger inicial para establecer la restricción
                endDateInput.setAttribute('min', startDateInput.value);
            }
        });
    </script>
</x-app-layout>
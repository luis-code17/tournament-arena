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
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Nuevo Torneo') }}
            </h2>
            <a href="{{ route('organization.tournaments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <form method="POST" action="{{ route('organization.tournaments.store') }}">
                        @csrf

                        <div class="form-section">
                            <label for="name" class="form-label">{{ __('Nombre del Torneo') }} <span class="required-mark">*</span></label>
                            <input id="name" class="form-input @error('name') border-red-500 @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus />
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-section">
                            <label for="desc" class="form-label">{{ __('Descripción') }}</label>
                            <textarea id="desc" name="desc" rows="4" class="form-input @error('desc') border-red-500 @enderror">{{ old('desc') }}</textarea>
                            @error('desc')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-grid">
                            <div class="form-section">
                                <label for="start_date" class="form-label">{{ __('Fecha de Inicio') }} <span class="required-mark">*</span></label>
                                <input id="start_date" class="form-input @error('start_date') border-red-500 @enderror" type="date" name="start_date" value="{{ old('start_date') }}" required />
                                @error('start_date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-section">
                                <label for="end_date" class="form-label">{{ __('Fecha de Finalización') }} <span class="required-mark">*</span></label>
                                <input id="end_date" class="form-input @error('end_date') border-red-500 @enderror" type="date" name="end_date" value="{{ old('end_date') }}" required />
                                @error('end_date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo de estado oculto porque en el controlador siempre se establece como 'pending' -->
                        <input type="hidden" name="state" value="pending">

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Crear Torneo') }}
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
            
            // Establecer la fecha mínima como hoy para la fecha de inicio
            const today = new Date().toISOString().split('T')[0];
            startDateInput.setAttribute('min', today);
            
            startDateInput.addEventListener('change', function() {
                // Actualizar la fecha mínima de finalización cuando cambie la fecha de inicio
                endDateInput.setAttribute('min', this.value);
                
                // Si la fecha de finalización es anterior a la de inicio, actualizarla
                if (endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
            });
        });
    </script>
</x-app-layout>
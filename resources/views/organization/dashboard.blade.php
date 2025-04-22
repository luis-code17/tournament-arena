<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Organización') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Bienvenido, {{ Auth::user()->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Gestión de Torneos -->
                        <div class="bg-amber-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-amber-700 mb-2">Gestión de Torneos</h4>
                            <p class="text-sm mb-3">Crea y administra tus torneos.</p>
                            <a href="#" class="text-sm text-amber-700 hover:underline">Gestionar torneos →</a>
                        </div>
                        
                        <!-- Equipos Participantes -->
                        <div class="bg-cyan-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-cyan-700 mb-2">Equipos Participantes</h4>
                            <p class="text-sm mb-3">Revisa los equipos registrados en tus torneos.</p>
                            <a href="#" class="text-sm text-cyan-700 hover:underline">Ver equipos →</a>
                        </div>
                        
                        <!-- Estadísticas y Reportes -->
                        <div class="bg-rose-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-rose-700 mb-2">Estadísticas</h4>
                            <p class="text-sm mb-3">Analiza el rendimiento de tus torneos y equipos.</p>
                            <a href="#" class="text-sm text-rose-700 hover:underline">Ver estadísticas →</a>
                        </div>
                    </div>
                    
                    <!-- Torneos Activos -->
                    <div class="mt-8">
                        <h4 class="font-semibold mb-4 border-b pb-2">Torneos Activos</h4>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">No hay torneos activos actualmente.</p>
                            <!-- Aquí podrías listar torneos activos desde la base de datos -->
                            
                            <div class="mt-4">
                                <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Crear nuevo torneo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
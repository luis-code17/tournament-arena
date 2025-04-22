<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Equipo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Bienvenido, {{ Auth::user()->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Torneos Disponibles -->
                        <div class="bg-blue-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-blue-700 mb-2">Torneos Disponibles</h4>
                            <p class="text-sm mb-3">Explora los torneos abiertos y regístrate para participar.</p>
                            <a href="#" class="text-sm text-blue-700 hover:underline">Ver torneos →</a>
                        </div>
                        
                        <!-- Mis Torneos -->
                        <div class="bg-green-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-green-700 mb-2">Mis Torneos</h4>
                            <p class="text-sm mb-3">Revisa los torneos en los que estás participando.</p>
                            <a href="#" class="text-sm text-green-700 hover:underline">Ver mis torneos →</a>
                        </div>
                        
                        <!-- Próximos Partidos -->
                        <div class="bg-purple-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-purple-700 mb-2">Próximos Partidos</h4>
                            <p class="text-sm mb-3">Consulta tu calendario de partidos programados.</p>
                            <a href="#" class="text-sm text-purple-700 hover:underline">Ver calendario →</a>
                        </div>
                    </div>
                    
                    <!-- Resumen de Actividad Reciente -->
                    <div class="mt-8">
                        <h4 class="font-semibold mb-4 border-b pb-2">Actividad Reciente</h4>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">No hay actividad reciente para mostrar.</p>
                            <!-- Aquí podrías listar actividades recientes desde la base de datos -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tournaments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Tabs -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('team.tournaments.index', ['filter' => 'pending']) }}" 
                           class="inline-flex items-center p-4 border-b-2 {{ request('filter', 'pending') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pending
                            @if(isset($pendingRegistrations) && $pendingRegistrations->count() > 0)
                                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-indigo-600 rounded-full">
                                    {{ $pendingRegistrations->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('team.tournaments.index', ['filter' => 'accepted']) }}" 
                           class="inline-flex items-center p-4 border-b-2 {{ request('filter') === 'accepted' ? 'border-indigo-500 text-indigo-600' : 'border-transparent hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Accepted
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('team.tournaments.index', ['filter' => 'rejected']) }}" 
                           class="inline-flex items-center p-4 border-b-2 {{ request('filter') === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Rejected
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Session Messages -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm leading-5 text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm leading-5 text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(request('filter', 'pending') === 'pending' && isset($pendingRegistrations))
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Tournament Registrations</h3>
                        @if($pendingRegistrations->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingRegistrations as $registration)
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="flex items-center justify-between p-4 bg-gray-50">
                                            <div>
                                                <h4 class="text-md font-semibold">{{ $registration->tournament->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending Approval
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($registration->tournament->start_date)->format('M d, Y') }}</span>
                                                <form action="{{ route('team.tournaments.cancel', $registration->tournament) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        Cancel Registration
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($registration->tournament->desc, 150) }}</p>
                                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($registration->tournament->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($registration->tournament->end_date)->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No pending registrations</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have any pending tournament registrations.</p>
                                <div class="mt-6">
                                    <a href="{{ route('tournaments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        View Available Tournaments
                                    </a>
                                </div>
                            </div>
                        @endif
                    @elseif(request('filter') === 'accepted' && isset($acceptedRegistrations))
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Accepted Tournament Registrations</h3>
                        @if($acceptedRegistrations->count() > 0)
                            <div class="space-y-4">
                                @foreach($acceptedRegistrations as $registration)
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="flex items-center justify-between p-4 bg-gray-50">
                                            <div>
                                                <h4 class="text-md font-semibold">{{ $registration->tournament->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Registration Accepted
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <a href="{{ route('tournaments.show', $registration->tournament) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    View Tournament
                                                </a>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($registration->tournament->desc, 150) }}</p>
                                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($registration->tournament->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($registration->tournament->end_date)->format('M d, Y') }}
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                Organized by: {{ $registration->tournament->organization->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No accepted registrations</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have any accepted tournament registrations yet.</p>
                            </div>
                        @endif
                    @elseif(request('filter') === 'rejected' && isset($rejectedRegistrations))
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rejected Tournament Registrations</h3>
                        @if($rejectedRegistrations->count() > 0)
                            <div class="space-y-4">
                                @foreach($rejectedRegistrations as $registration)
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="flex items-center justify-between p-4 bg-gray-50">
                                            <div>
                                                <h4 class="text-md font-semibold">{{ $registration->tournament->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Registration Rejected
                                                    </span>
                                                </p>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($registration->updated_at)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($registration->tournament->desc, 150) }}</p>
                                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($registration->tournament->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($registration->tournament->end_date)->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No rejected registrations</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have any rejected tournament registrations.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom CSS for SVG icons and spacing */
        svg {
            display: inline-block;
            vertical-align: middle;
        }
        
        .inline-flex {
            display: inline-flex;
            align-items: center;
        }
        
        /* Status badge styling */
        .rounded-full {
            border-radius: 9999px;
        }
        
        /* Card hover effects */
        .border {
            transition: all 0.2s ease-in-out;
        }
        
        .border:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Tab styling */
        .border-b-2 {
            transition: all 0.2s ease;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .flex {
                flex-direction: column;
            }
            
            .justify-between {
                justify-content: flex-start;
            }
            
            .space-x-2 > * + * {
                margin-top: 0.5rem;
                margin-left: 0;
            }
            
            .p-4 {
                padding: 1rem 0.75rem;
            }
        }
    </style>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Join Tournament') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <div class="flex flex-col md:flex-row md:space-x-6">
                        <!-- Tournament Details -->
                        <div class="md:w-2/3">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $tournament->name }}</h3>
                            
                            <div class="mb-4 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($tournament->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="mb-4 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Organized by: {{ $tournament->organization->name }}</span>
                            </div>
                            
                            <div class="mb-4 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ $tournament->acceptedTeams->count() }} Teams Participating</span>
                            </div>
                            
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-700 mb-2">Tournament Description</h4>
                                <div class="prose prose-sm max-w-none text-gray-600">
                                    {{ $tournament->desc }}
                                </div>
                            </div>
                            
                            @if($tournament->isPending())
                                <div class="bg-yellow-50 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>This tournament is currently in the registration phase. Your team's participation will be subject to approval by the organizer.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Join Form -->
                        <div class="md:w-1/3 mt-6 md:mt-0">
                            <div class="bg-gray-50 p-6 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Join Tournament</h3>
                                
                                @if($existingRegistration)
                                    <div class="rounded-md bg-blue-50 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                                <p class="text-sm text-blue-700">
                                                    Your team is already registered for this tournament. 
                                                    @if($existingRegistration->isPending())
                                                        Your registration is pending approval.
                                                    @elseif($existingRegistration->isAccepted())
                                                        Your registration has been accepted.
                                                    @elseif($existingRegistration->isRejected())
                                                        Your registration has been rejected.
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($existingRegistration->isPending())
                                        <div class="mt-4">
                                            <form action="{{ route('team.tournaments.cancel', $tournament) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your registration?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Cancel Registration
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @elseif(!$tournament->isOpenForRegistration())
                                    <div class="rounded-md bg-yellow-50 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    This tournament is not open for registration at this time.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('team.tournaments.register', $tournament) }}" method="POST">
                                        @csrf
                                        <div class="rounded-md bg-gray-100 p-4 mb-4">
                                            <div class="flex">
                                                <div class="ml-3">
                                                    <p class="text-sm text-gray-700">
                                                        By registering, you agree to abide by all tournament rules and the organizer's decisions.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Register for Tournament
                                        </button>
                                    </form>
                                @endif
                                
                                <div class="mt-4">
                                    <a href="{{ route('team.tournaments.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Back to Tournaments
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
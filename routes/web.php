<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamTournamentController;
use App\Http\Controllers\MatchesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas accesibles para todos los usuarios autenticados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Redirección basada en el tipo de usuario
        if (auth()->user()->isTeam()) {
            return redirect('/team/dashboard');
        } elseif (auth()->user()->isOrganization()) {
            return redirect('/organization/dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

// Rutas específicas para equipos
Route::middleware(['auth', 'verified'])->prefix('team')->group(function () {
    Route::get('/dashboard', function () {
        if (!auth()->user()->isTeam()) {
            abort(403);
        }
        return view('team.dashboard');
    })->name('team.dashboard');
    
    // Aquí puedes agregar más rutas específicas para equipos
});

// Rutas específicas para organizaciones
Route::middleware(['auth', 'verified'])->prefix('organization')->group(function () {
    Route::get('/dashboard', function () {
        if (!auth()->user()->isOrganization()) {
            abort(403);
        }
        return view('organization.dashboard');
    })->name('organization.dashboard');
    
    // Aquí puedes agregar más rutas específicas para organizaciones
});

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//rutas de tournament en organization
Route::middleware(['auth', 'verified'])->prefix('organization/tournaments')->group(function () {
    Route::get('/', [TournamentController::class, 'index'])->name('organization.tournaments.index');
    Route::get('/create', [TournamentController::class, 'create'])->name('organization.tournaments.create');
    Route::post('/', [TournamentController::class, 'store'])->name('organization.tournaments.store');
    Route::get('/{tournament}', [TournamentController::class, 'show'])->name('organization.tournaments.show');
    
    // Rutas faltantes
    Route::get('/{tournament}/edit', [TournamentController::class, 'edit'])->name('organization.tournaments.edit');
    Route::put('/{tournament}', [TournamentController::class, 'update'])->name('organization.tournaments.update');
    Route::delete('/{tournament}', [TournamentController::class, 'destroy'])->name('organization.tournaments.destroy');
    
    // Rutas para iniciar y finalizar torneos
    Route::post('/{tournament}/start', [TournamentController::class, 'start'])->name('organization.tournaments.start');
    Route::post('/{tournament}/finish', [TournamentController::class, 'finish'])->name('organization.tournaments.finish');
    
    // Ruta para ver los equipos de un torneo
    Route::get('/{tournament}/teams', [TournamentController::class, 'teams'])->name('organization.tournaments.teams');
});

// Rutas para la gestión de solicitudes de equipos
Route::middleware(['auth', 'verified'])->prefix('organization/requests')->group(function () {
    Route::get('/', [TeamTournamentController::class, 'index'])->name('organization.requests.index');
    Route::post('/{teamTournament}/accept', [TeamTournamentController::class, 'accept'])->name('organization.requests.accept');
    Route::post('/{teamTournament}/reject', [TeamTournamentController::class, 'reject'])->name('organization.requests.reject');
    Route::delete('/{teamTournament}', [TeamTournamentController::class, 'remove'])->name('organization.requests.remove');
});


// rutas matches en organization
Route::middleware(['auth', 'verified'])->prefix('organization/matches')->group(function () {
    Route::get('/', [TournamentController::class, 'index'])->name('organization.matches.index');
    Route::get('/create', [TournamentController::class, 'create'])->name('organization.matches.create');
    Route::post('/', [TournamentController::class, 'store'])->name('organization.matches.store');
    Route::get('/{match}', [TournamentController::class, 'show'])->name('organization.matches.show');
});

// Rutas para torneos (accesible para equipos)
Route::middleware(['auth', 'verified'])->group(function () {
    // Vista de torneos disponibles
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('team.tournaments.index');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('team.tournaments.show');
    Route::get('/tournaments/{tournament}/join', [TournamentController::class, 'join'])->name('team.tournaments.join');
});

// Rutas para inscripción a torneos (específico para equipos)
Route::middleware(['auth', 'verified'])->prefix('team/tournaments')->group(function () {
    Route::get('/', [TeamTournamentController::class, 'myTournaments'])->name('team.tournaments.index');
    // Añade esta línea para la ruta join
    Route::get('/{tournament}/join', [TeamTournamentController::class, 'join'])->name('team.tournaments.join');
    Route::post('/{tournament}/register', [TeamTournamentController::class, 'register'])->name('team.tournaments.register');
    Route::delete('/{tournament}/cancel', [TeamTournamentController::class, 'cancel'])->name('team.tournaments.cancel');
});
// Rutas públicas para torneos (sin autenticación)
Route::get('/tournaments', [TournamentController::class, 'publicIndex'])->name('public.tournaments.index');
require __DIR__.'/auth.php';
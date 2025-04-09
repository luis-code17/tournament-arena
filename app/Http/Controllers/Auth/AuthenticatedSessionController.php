<?php

use App\Http\Controllers\ProfileController;
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

require __DIR__ .'/../../../../routes/auth.php';
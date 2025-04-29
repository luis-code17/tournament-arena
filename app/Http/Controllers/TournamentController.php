<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    /**
     * Display a listing of the tournaments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Cambiar de get() a paginate()
        $tournaments = Tournament::where('organization_id', Auth::id())
            ->withCount('acceptedTeams')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginar con 10 elementos por página
        
        return view('organization.tournaments.index', compact('tournaments'));
    }

    /**
     * Show the form for creating a new tournament.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if user is an organization
        if (!Auth::user()->isOrganization()) {
            return redirect()->route('organization.tournaments.index')
                ->with('error', 'Only organizations can create tournaments.');
        }

        return view('organization.tournaments.create');
    }

    /**
     * Store a newly created tournament in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if user is an organization
        if (!Auth::user()->isOrganization()) {
            return redirect()->route('organization.tournaments.index')
                ->with('error', 'Only organizations can create tournaments.');
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create tournament
        $tournament = new Tournament();
        $tournament->name = $request->name;
        $tournament->desc = $request->desc;
        $tournament->start_date = $request->start_date;
        $tournament->end_date = $request->end_date;
        $tournament->organization_id = Auth::id();
        $tournament->state = 'pending';
        $tournament->save();

        return redirect()->route('organization.tournaments.show', $tournament->id)
            ->with('success', 'Tournament created successfully.');
    }

    /**
     * Display the specified tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        $tournament->load(['organization', 'acceptedTeams']);
        
        // Get matches for this tournament if any
        $matches = $tournament->matches ?? collect();
        
        return view('organization.tournaments.show', compact('tournament', 'matches'));
    }

    /**
     * Show the form for editing the specified tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to edit this tournament.');
        }

        return view('organization.tournaments.edit', compact('tournament'));
    }

    /**
     * Update the specified tournament in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to edit this tournament.');
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'state' => 'required|in:pending,in_progress,finished',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update tournament
        $tournament->name = $request->name;
        $tournament->desc = $request->desc;
        $tournament->start_date = $request->start_date;
        $tournament->end_date = $request->end_date;
        $tournament->state = $request->state;
        $tournament->save();

        return redirect()->route('organization.tournaments.show', $tournament->id)
            ->with('success', 'Tournament updated successfully.');
    }

    /**
     * Remove the specified tournament from storage.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to delete this tournament.');
        }

        try {
            // Desactivar temporalmente las restricciones de clave foránea para SQLite
            if (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            }

            // Eliminar primero las relaciones de forma manual
            if (Schema::hasTable('team_tournament')) {
                DB::table('team_tournament')->where('tournament_id', $tournament->id)->delete();
            }
            
            if (Schema::hasTable('matches')) {
                DB::table('matches')->where('tournament_id', $tournament->id)->delete();
            }
            
            // Ahora eliminar el torneo
            $tournament->delete();
            
            // Reactivar las restricciones de clave foránea
            if (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
            
            return redirect()->route('organization.tournaments.index')
                ->with('success', 'Tournament deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('organization.tournaments.index')
                ->with('error', 'Error deleting tournament: ' . $e->getMessage());
        }
    }

    /**
     * Display the teams registered for the tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function teams(Tournament $tournament)
    {
        $tournament->load(['teamTournaments.team']);
        
        $pendingTeams = $tournament->teamTournaments()->pending()->with('team')->get();
        $acceptedTeams = $tournament->teamTournaments()->accepted()->with('team')->get();
        $rejectedTeams = $tournament->teamTournaments()->rejected()->with('team')->get();
        
        return view('organization.tournaments.teams', compact('tournament', 'pendingTeams', 'acceptedTeams', 'rejectedTeams'));
    }

    /**
     * Start the tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function start(Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to start this tournament.');
        }

        // Check if tournament is in pending state
        if (!$tournament->isPending()) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'Tournament can only be started if it is in pending state.');
        }

        // Check if there are at least 2 accepted teams
        if ($tournament->acceptedTeams()->count() < 2) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'Tournament needs at least 2 teams to start.');
        }

        // Update tournament state
        $tournament->state = 'in_progress';
        $tournament->save();

        return redirect()->route('organization.tournaments.show', $tournament->id)
            ->with('success', 'Tournament started successfully.');
    }

    /**
     * Finish the tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function finish(Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to finish this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('organization.tournaments.show', $tournament->id)
                ->with('error', 'Tournament can only be finished if it is in progress.');
        }

        // Update tournament state
        $tournament->state = 'finished';
        $tournament->save();

        return redirect()->route('organization.tournaments.show', $tournament->id)
            ->with('success', 'Tournament finished successfully.');
    }

    /**
     * Display a public listing of all tournaments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function publicIndex(Request $request)
    {
        $query = Tournament::with('organization')
            ->withCount('acceptedTeams');
        
        // Apply filter if provided
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if (in_array($filter, ['pending', 'in_progress', 'finished'])) {
                $query->where('state', $filter);
            }
        }
        
        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('desc', 'like', "%{$search}%");
            });
        }
        
        $tournaments = $query->orderBy('created_at', 'desc')
            ->paginate(9);
        
        return view('public.tournaments.index', compact('tournaments'));
    }
}
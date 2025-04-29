<?php

namespace App\Http\Controllers;

use App\Models\TeamTournament;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamTournamentController extends Controller
{
    /**
     * Register a team for a tournament.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, Tournament $tournament)
    {
        // Check if user is a team
        if (!Auth::user()->isTeam()) {
            return redirect()->route('dashboard')
                ->with('error', 'Only teams can register for tournaments.');
        }
    
        // Check if tournament is open for registration
        if (!$tournament->isOpenForRegistration()) {
            return redirect()->route('dashboard')
                ->with('error', 'This tournament is not open for registration.');
        }
    
        // Check if team is already registered
        $existingRegistration = TeamTournament::where('tournament_id', $tournament->id)
            ->where('user_id', Auth::id())
            ->first();
    
        if ($existingRegistration) {
            return redirect()->route('dashboard')
                ->with('error', 'Your team is already registered for this tournament.');
        }
    
        // Create the registration
        $teamTournament = new TeamTournament();
        $teamTournament->tournament_id = $tournament->id;
        $teamTournament->user_id = Auth::id();
        $teamTournament->state = 'pending';
        $teamTournament->save();
    
        return redirect()->route('dashboard')
            ->with('success', 'Your team has been registered for this tournament.');
    }
    
    /**
     * Cancel a team's registration for a tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function cancel(Tournament $tournament)
    {
        // Check if user is a team
        if (!Auth::user()->isTeam()) {
            return redirect()->route('team.tournaments.join', $tournament)
                ->with('error', 'Only teams can cancel tournament registrations.');
        }

        // Find the registration
        $teamTournament = TeamTournament::where('tournament_id', $tournament->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$teamTournament) {
            return redirect()->route('team.tournaments.join', $tournament)
                ->with('error', 'Your team is not registered for this tournament.');
        }

        // Check if tournament is still in pending state
        if (!$tournament->isPending()) {
            return redirect()->route('team.tournaments.join', $tournament)
                ->with('error', 'You cannot cancel registration for a tournament that has already started or finished.');
        }

        // Delete the registration
        $teamTournament->delete();

        return redirect()->route('team.tournaments.index')
            ->with('success', 'Your team registration has been cancelled.');
    }

    /**
     * Accept a team's registration for a tournament.
     *
     * @param  \App\Models\TeamTournament  $teamTournament
     * @return \Illuminate\Http\Response
     */
    public function accept(TeamTournament $teamTournament)
    {
        $tournament = $teamTournament->tournament;

        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.requests.index')
                ->with('error', 'You are not authorized to manage team registrations for this tournament.');
        }

        // Check if tournament is still in pending state
        if (!$tournament->isPending()) {
            return redirect()->route('organization.requests.index')
                ->with('error', 'You cannot modify team registrations for a tournament that has already started or finished.');
        }

        // Update the registration state
        $teamTournament->state = 'accepted';
        $teamTournament->save();

        return redirect()->route('organization.requests.index')
            ->with('success', 'Team registration has been accepted.');
    }

    /**
     * Reject a team's registration for a tournament.
     *
     * @param  \App\Models\TeamTournament  $teamTournament
     * @return \Illuminate\Http\Response
     */
    public function reject(TeamTournament $teamTournament)
    {
        $tournament = $teamTournament->tournament;

        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.requests.index')
                ->with('error', 'You are not authorized to manage team registrations for this tournament.');
        }

        // Check if tournament is still in pending state
        if (!$tournament->isPending()) {
            return redirect()->route('organization.requests.index')
                ->with('error', 'You cannot modify team registrations for a tournament that has already started or finished.');
        }

        // Update the registration state
        $teamTournament->state = 'rejected';
        $teamTournament->save();

        return redirect()->route('organization.requests.index')
            ->with('success', 'Team registration has been rejected.');
    }

    /**
     * Remove a team from a tournament.
     *
     * @param  \App\Models\TeamTournament  $teamTournament
     * @return \Illuminate\Http\Response
     */
    public function remove(TeamTournament $teamTournament)
    {
        $tournament = $teamTournament->tournament;

        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.tournaments.teams', $tournament->id)
                ->with('error', 'You are not authorized to manage team registrations for this tournament.');
        }

        // Check if tournament is still in pending state
        if (!$tournament->isPending()) {
            return redirect()->route('organization.tournaments.teams', $tournament->id)
                ->with('error', 'You cannot modify team registrations for a tournament that has already started or finished.');
        }

        // Delete the registration
        $teamTournament->delete();

        return redirect()->route('organization.tournaments.teams', $tournament->id)
            ->with('success', 'Team has been removed from the tournament.');
    }

    /**
     * Display the tournaments a team is registered for.
     *
     * @return \Illuminate\Http\Response
     */
    public function myTournaments()
    {
        // Check if user is a team
        if (!Auth::user()->isTeam()) {
            return redirect()->route('dashboard')
                ->with('error', 'Only teams can view their tournament registrations.');
        }

        $pendingRegistrations = TeamTournament::where('user_id', Auth::id())
            ->where('state', 'pending')
            ->with('tournament')
            ->get();

        $acceptedRegistrations = TeamTournament::where('user_id', Auth::id())
            ->where('state', 'accepted')
            ->with('tournament')
            ->get();

        $rejectedRegistrations = TeamTournament::where('user_id', Auth::id())
            ->where('state', 'rejected')
            ->with('tournament')
            ->get();

        return view('team.tournaments.index', compact(
            'pendingRegistrations',
            'acceptedRegistrations',
            'rejectedRegistrations'
        ));
    }

    /**
     * Display a listing of available tournaments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is an organization
        if (!Auth::user()->isOrganization()) {
            return redirect()->route('dashboard')
                ->with('error', 'Only organizations can view team requests.');
        }

        $filter = $request->query('filter', 'pending');
        
        // Get tournament IDs for this organization
        $tournamentIds = Tournament::where('organization_id', Auth::id())->pluck('id');
        
        // Query team tournament requests based on filter
        $query = TeamTournament::whereIn('tournament_id', $tournamentIds)
            ->with(['team', 'tournament']);
            
        if ($filter === 'pending') {
            $query->where('state', 'pending');
        } elseif ($filter === 'accepted') {
            $query->where('state', 'accepted');
        } elseif ($filter === 'rejected') {
            $query->where('state', 'rejected');
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Count pending requests for badge
        $pendingCount = TeamTournament::whereIn('tournament_id', $tournamentIds)
            ->where('state', 'pending')
            ->count();
        
        return view('organization.requests.index', compact('requests', 'pendingCount', 'filter'));
    }
    
    /**
     * Show the form for joining a tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function join(Tournament $tournament)
    {
        // Check if user is a team
        if (!Auth::user()->isTeam()) {
            return redirect()->route('dashboard')
                ->with('error', 'Solo los equipos pueden unirse a torneos.');
        }

        // Check if tournament is open for registration
        if (!$tournament->isOpenForRegistration()) {
            return redirect()->route('team.tournaments.index')
                ->with('error', 'Este torneo no está abierto para inscripciones.');
        }

        // Check if team is already registered
        $existingRegistration = TeamTournament::where('tournament_id', $tournament->id)
            ->where('user_id', Auth::id())
            ->first();

        return view('team.tournaments.join', compact('tournament', 'existingRegistration'));
    }
}
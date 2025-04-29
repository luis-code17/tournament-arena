<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MatchesController extends Controller
{
    /**
     * Display a listing of matches within a specific tournament.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function index(Tournament $tournament)
    {
        // Check if user is the owner of the tournament or if the tournament is public
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to view matches for this tournament.');
        }

        $matches = $tournament->matches()->with(['teamOne', 'teamTwo'])->orderByDesc('date')->paginate(10);

        return view('organization.matches.index', compact('tournament', 'matches'));
    }

    /**
     * Show the form for creating a new match.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function create(Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to create matches for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('tournaments.show', $tournament->id)
                ->with('error', 'Matches can only be created for tournaments that are in progress.');
        }

        // Get accepted teams for the tournament
        $teams = $tournament->acceptedTeams;

        return view('organization.matches.create', compact('tournament', 'teams'));
    }

    /**
     * Store a newly created match in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tournament $tournament)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('tournaments.show', $tournament->id)
                ->with('error', 'You are not authorized to create matches for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('tournaments.show', $tournament->id)
                ->with('error', 'Matches can only be created for tournaments that are in progress.');
        }

        // Validate request
        $request->validate([
            'team_1' => ['required', 'exists:users,id', 'different:team_2'],
            'team_2' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'result' => ['nullable', 'string'],
        ]);

        // Create match
        $match = new Match();
        $match->tournament_id = $tournament->id;
        $match->team_1 = $request->team_1;
        $match->team_2 = $request->team_2;
        $match->date = $request->date;
        $match->result = $request->result;
        $match->save();

        return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
            ->with('success', 'Match created successfully.');
    }

    /**
     * Display the specified match.
     *
     * @param  \App\Models\Tournament  $tournament
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament, Match $match)
    {
        // Load relationships
        $match->load(['teamOne', 'teamTwo']);

        return view('organization.matches.show', compact('tournament', 'match'));
    }

    /**
     * Show the form for editing the specified match.
     *
     * @param  \App\Models\Tournament  $tournament
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament, Match $match)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'You are not authorized to edit matches for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'Matches can only be edited for tournaments that are in progress.');
        }

        // Get accepted teams for the tournament
        $teams = $tournament->acceptedTeams;

        return view('organization.matches.edit', compact('tournament', 'match', 'teams'));
    }

    /**
     * Update the specified match in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament, Match $match)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'You are not authorized to update matches for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'Matches can only be updated for tournaments that are in progress.');
        }

        // Validate request
        $request->validate([
            'team_1' => ['required', 'exists:users,id', 'different:team_2'],
            'team_2' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'result' => ['nullable', 'string'],
        ]);

        // Update match
        $match->team_1 = $request->team_1;
        $match->team_2 = $request->team_2;
        $match->date = $request->date;
        $match->result = $request->result;
        $match->save();

        return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
            ->with('success', 'Match updated successfully.');
    }

    /**
     * Remove the specified match from storage.
     *
     * @param  \App\Models\Tournament  $tournament
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament, Match $match)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'You are not authorized to delete matches for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'Matches can only be deleted for tournaments that are in progress.');
        }

        // Delete match
        $match->delete();

        return redirect()->route('organization.matches.index', $tournament->id)
            ->with('success', 'Match deleted successfully.');
    }

    /**
     * Set the result for a match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function setResult(Request $request, Tournament $tournament, Match $match)
    {
        // Check if user is the owner of the tournament
        if (Auth::id() !== $tournament->organization_id) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'You are not authorized to set results for this tournament.');
        }

        // Check if tournament is in progress
        if (!$tournament->isInProgress()) {
            return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
                ->with('error', 'Results can only be set for tournaments that are in progress.');
        }

        // Validate request
        $request->validate([
            'team_1_score' => ['required', 'integer', 'min:0'],
            'team_2_score' => ['required', 'integer', 'min:0'],
        ]);

        // Set result using the format "team_1_score-team_2_score"
        $match->result = $request->team_1_score . '-' . $request->team_2_score;
        $match->save();

        return redirect()->route('organization.matches.show', [$tournament->id, $match->id])
            ->with('success', 'Match result set successfully.');
    }
}
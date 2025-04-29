<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tournament_id',
        'team_1',
        'team_2',
        'date',
        'result'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the tournament that owns the match.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the first team in the match.
     */
    public function teamOne()
    {
        return $this->belongsTo(User::class, 'team_1');
    }

    /**
     * Get the second team in the match.
     */
    public function teamTwo()
    {
        return $this->belongsTo(User::class, 'team_2');
    }

    /**
     * Get both teams in the match.
     */
    public function teams()
    {
        return User::whereIn('id', [$this->team_1, $this->team_2])->get();
    }

    /**
     * Check if the match has a result.
     */
    public function hasResult()
    {
        return !is_null($this->result);
    }

    /**
     * Check if the match has been played.
     */
    public function isPlayed()
    {
        return $this->hasResult();
    }

    /**
     * Check if the match is pending to be played.
     */
    public function isPending()
    {
        return !$this->hasResult();
    }

    /**
     * Check if the match is scheduled for the future.
     */
    public function isFuture()
    {
        return now()->lessThan($this->date);
    }

    /**
     * Check if the match is scheduled for the past.
     */
    public function isPast()
    {
        return now()->greaterThan($this->date);
    }

    /**
     * Get the winner of the match.
     * Returns null if there's no result or it's a draw.
     */
    public function getWinner()
    {
        if (!$this->hasResult()) {
            return null;
        }

        // Assuming the result is stored as "team_1-team_2" format (e.g., "3-1")
        $scores = explode('-', $this->result);
        
        if (count($scores) !== 2) {
            return null;
        }
        
        $score1 = (int) $scores[0];
        $score2 = (int) $scores[1];
        
        if ($score1 > $score2) {
            return $this->teamOne;
        } elseif ($score2 > $score1) {
            return $this->teamTwo;
        }
        
        // If it's a draw
        return null;
    }

    /**
     * Check if the match resulted in a draw.
     */
    public function isDraw()
    {
        if (!$this->hasResult()) {
            return false;
        }

        $scores = explode('-', $this->result);
        
        if (count($scores) !== 2) {
            return false;
        }
        
        return $scores[0] === $scores[1];
    }

    /**
     * Get the scores as an array [team1_score, team2_score].
     */
    public function getScores()
    {
        if (!$this->hasResult()) {
            return [null, null];
        }

        $scores = explode('-', $this->result);
        
        if (count($scores) !== 2) {
            return [null, null];
        }
        
        return [(int) $scores[0], (int) $scores[1]];
    }

    /**
     * Set the result of the match.
     */
    public function setResult($team1Score, $team2Score)
    {
        $this->result = $team1Score . '-' . $team2Score;
        return $this;
    }

    /**
     * Scope a query to only include matches from a specific tournament.
     */
    public function scopeInTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Scope a query to only include matches involving a specific team.
     */
    public function scopeInvolvingTeam($query, $teamId)
    {
        return $query->where('team_1', $teamId)->orWhere('team_2', $teamId);
    }

    /**
     * Scope a query to only include matches that have been played.
     */
    public function scopePlayed($query)
    {
        return $query->whereNotNull('result');
    }

    /**
     * Scope a query to only include pending matches.
     */
    public function scopePending($query)
    {
        return $query->whereNull('result');
    }

    /**
     * Scope a query to only include upcoming matches (future date).
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>', now());
    }

    /**
     * Scope a query to only include past matches (past date).
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }
}

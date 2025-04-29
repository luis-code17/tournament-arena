<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'desc',
        'start_date',
        'end_date',
        'organization_id',
        'state'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'state' => 'string',
    ];

    /**
     * Get the organization that owns the tournament.
     */
    public function organization()
    {
        return $this->belongsTo(User::class, 'organization_id');
    }

    /**
     * Get the teams that are registered for this tournament.
     */
    public function teams()
    {
        return $this->belongsToMany(User::class, 'team_tournaments')
                    ->withPivot('state')
                    ->withTimestamps();
    }

    /**
     * Get the team registrations for this tournament.
     */
    public function teamTournaments()
    {
        return $this->hasMany(TeamTournament::class);
    }

    /**
     * Get the accepted teams for this tournament.
     */
    public function acceptedTeams()
    {
        return $this->belongsToMany(User::class, 'team_tournaments')
                    ->wherePivot('state', 'accepted')
                    ->withTimestamps();
    }

    /**
     * Get the pending team registrations for this tournament.
     */
    public function pendingTeams()
    {
        return $this->belongsToMany(User::class, 'team_tournaments')
                    ->wherePivot('state', 'pending')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include pending tournaments.
     */
    public function scopePending($query)
    {
        return $query->where('state', 'pending');
    }

    /**
     * Scope a query to only include in progress tournaments.
     */
    public function scopeInProgress($query)
    {
        return $query->where('state', 'in_progress');
    }

    /**
     * Scope a query to only include finished tournaments.
     */
    public function scopeFinished($query)
    {
        return $query->where('state', 'finished');
    }

    /**
     * Check if the tournament is pending.
     */
    public function isPending()
    {
        return $this->state === 'pending';
    }

    /**
     * Check if the tournament is in progress.
     */
    public function isInProgress()
    {
        return $this->state === 'in_progress';
    }

    /**
     * Check if the tournament is finished.
     */
    public function isFinished()
    {
        return $this->state === 'finished';
    }

    /**
     * Check if the tournament is open for registration.
     */
    public function isOpenForRegistration()
    {
        return $this->state === 'pending';
    }

    /**
     * Check if tournament has already started.
     */
    public function hasStarted()
    {
        return now()->greaterThanOrEqualTo($this->start_date);
    }

    /**
     * Check if tournament has already ended.
     */
    public function hasEnded()
    {
        return now()->greaterThan($this->end_date);
    }
}
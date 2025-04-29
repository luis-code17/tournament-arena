<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamTournament extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_tournaments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tournament_id',
        'user_id',  // Usuario con type='team'
        'state'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'state' => 'string',
    ];

    /**
     * Get the tournament that the team belongs to.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the team (user) that is associated with the tournament.
     */
    public function team()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('state', 'pending');
    }

    /**
     * Scope a query to only include accepted registrations.
     */
    public function scopeAccepted($query)
    {
        return $query->where('state', 'accepted');
    }

    /**
     * Scope a query to only include rejected registrations.
     */
    public function scopeRejected($query)
    {
        return $query->where('state', 'rejected');
    }

    /**
     * Check if the registration is pending.
     */
    public function isPending()
    {
        return $this->state === 'pending';
    }

    /**
     * Check if the registration is accepted.
     */
    public function isAccepted()
    {
        return $this->state === 'accepted';
    }

    /**
     * Check if the registration is rejected.
     */
    public function isRejected()
    {
        return $this->state === 'rejected';
    }
}

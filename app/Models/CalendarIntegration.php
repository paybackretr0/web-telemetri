<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarIntegration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'google_calendar_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the calendar integration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the calendar events for the calendar integration.
     */
    public function calendarEvents()
    {
        return $this->hasMany(ActivityCalendarEvent::class);
    }
}
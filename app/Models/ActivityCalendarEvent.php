<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCalendarEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'activity_id',
        'user_id',
        'google_event_id',
        'synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'synced_at' => 'datetime',
    ];

    /**
     * Get the activity that owns the calendar event.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the user that owns the calendar event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
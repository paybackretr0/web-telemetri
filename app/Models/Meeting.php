<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'meeting_notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meeting_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the user that created the meeting.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attendances for the meeting.
     */
    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    /**
     * Get the users who attended the meeting.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_attendances')
                    ->withPivot('status', 'check_in_time')
                    ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
        'check_in_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    /**
     * Get the meeting that owns the attendance.
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
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
        'location',
        'latitude',
        'longitude',
        'start_time',
        'end_time',
        'qr_code',
        'attendance_type_id',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the attendance type that owns the activity.
     */
    public function attendanceType()
    {
        return $this->belongsTo(AttendanceType::class);
    }

    /**
     * Get the user that created the activity.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attendances for the activity.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the permissions for the activity.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Get the delegations for the activity.
     */
    public function delegations()
    {
        return $this->hasMany(Delegation::class);
    }

    /**
     * Get the programs for the activity.
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_activities')
                    ->withTimestamps();
    }

    /**
     * Get the QR code for the activity.
     */
    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    /**
     * Get the calendar events for the activity.
     */
    public function calendarEvents()
    {
        return $this->hasMany(ActivityCalendarEvent::class);
    }
}
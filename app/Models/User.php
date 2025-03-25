<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
        'phone_number',
        'google_id',
        'google_token',
        'google_refresh_token',
        'nim',
        'jurusan',
        'angkatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's created activities.
     */
    public function createdActivities()
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    /**
     * Get the user's attendances.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the user's permissions.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Get the delegations requested by the user.
     */
    public function requestedDelegations()
    {
        return $this->hasMany(Delegation::class, 'requester_id');
    }

    /**
     * Get the delegations where the user is a delegate.
     */
    public function delegatedTasks()
    {
        return $this->hasMany(Delegation::class, 'delegate_id');
    }

    /**
     * Get the programs created by the user.
     */
    public function createdPrograms()
    {
        return $this->hasMany(Program::class, 'created_by');
    }

    /**
     * Get the programs the user is part of.
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'user_programs')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's calendar integrations.
     */
    public function calendarIntegrations()
    {
        return $this->hasMany(CalendarIntegration::class);
    }

    /**
     * Get the user's QR codes.
     */
    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'created_by');
    }

    /**
     * Get the user's sessions.
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get the meetings created by the user.
     */
    public function createdMeetings()
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    /**
     * Get the user's meeting attendances.
     */
    public function meetingAttendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    /**
     * Get the user's duty schedules.
     */
    public function dutySchedules()
    {
        return $this->belongsToMany(DutySchedule::class, 'user_duty_schedules')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    /**
     * Get the announcements created by the user.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

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
        'nomor_seri',
        'jabatan',
        'divisi',
        'sub_divisi',
        'device_token',
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
     * Get the delegations assigned to the user.
     */
    public function assignedDelegations()
    {
        return $this->hasMany(Delegation::class, 'delegate_id');
    }

    /**
     * Get the delegations approved by the user.
     */
    public function approvedDelegations()
    {
        return $this->hasMany(Delegation::class, 'approved_by');
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
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

    public function getProfilePictureAttribute($value)
    {
        if (!$value) return null;
        return Storage::disk('public')->exists($value)
            ? url(Storage::url($value))
            : null;
    }
}
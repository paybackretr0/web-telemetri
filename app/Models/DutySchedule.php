<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutySchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'location',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the users for the duty schedule.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_duty_schedules')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }
    
    /**
     * Get the delegations for this duty schedule.
     */
    public function delegations()
    {
        return $this->hasMany(Delegation::class);
    }
}
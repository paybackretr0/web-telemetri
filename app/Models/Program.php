<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
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
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that created the program.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users for the program.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_programs')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the activities for the program.
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'program_activities')
                    ->withTimestamps();
    }
}
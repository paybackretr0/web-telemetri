<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'requester_id',
        'delegate_id',
        'activity_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that requested the delegation.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the user that is the delegate.
     */
    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    /**
     * Get the activity that owns the delegation.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the user that approved the delegation.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
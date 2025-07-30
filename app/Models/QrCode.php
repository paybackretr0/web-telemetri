<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'activity_id',
        'meeting_id',
        'code',
        'file_path',
        'type',
        'expiry_time',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiry_time' => 'datetime',
    ];

    /**
     * Get the activity that owns the QR code.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the user that created the QR code.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
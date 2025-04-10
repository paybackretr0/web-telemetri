<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DutySchedule;
use App\Models\User;
use App\Models\UserDutySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DutyScheduleController extends Controller
{
    /**
     * Get the authenticated user's duty schedules.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myDutySchedules()
    {
        $user = Auth::user();
        
        $userDutySchedules = UserDutySchedule::with('dutySchedule')
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->get();
            
        $dutySchedules = $userDutySchedules->map(function($userDutySchedule) {
            $schedule = $userDutySchedule->dutySchedule;
            $schedule->start_date = $userDutySchedule->start_date;
            $schedule->end_date = $userDutySchedule->end_date;
            return $schedule;
        });
        
        return response()->json([
            'success' => true,
            'data' => $dutySchedules,
        ]);
    }
    
    /**
     * Get all users who can be potential delegates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPotentialDelegates()
    {
        // Get all users except the current user
        $users = User::where('id', '!=', Auth::id())
            ->where('role', '!=', 'admin') // Optionally exclude admins
            ->select('id', 'name', 'email', 'profile_picture')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
    
    /**
     * Get the next duty schedule for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNextDuty()
    {
        $user = Auth::user();
        $today = now();
        $dayOfWeek = $today->dayOfWeek;
        
        // Get all active duty schedules for the user
        $userDutySchedules = UserDutySchedule::with('dutySchedule')
            ->where('user_id', $user->id)
            ->where(function($query) use ($today) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $today->format('Y-m-d'));
            })
            ->whereHas('dutySchedule')
            ->get();
            
        if ($userDutySchedules->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No upcoming duty schedules found',
                'data' => null,
            ]);
        }
        
        // Find the next duty schedule
        $nextDuty = null;
        $daysUntilNext = 7; // Maximum days in a week
        
        foreach ($userDutySchedules as $userDutySchedule) {
            $scheduleDayOfWeek = $userDutySchedule->dutySchedule->day_of_week;
            
            // Calculate days until this duty
            $daysUntil = ($scheduleDayOfWeek - $dayOfWeek + 7) % 7;
            if ($daysUntil === 0) {
                // If it's today, check if the time has passed
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $userDutySchedule->dutySchedule->start_time);
                if ($today->format('H:i') > $startTime->format('H:i')) {
                    $daysUntil = 7; // Schedule for next week
                }
            }
            
            if ($daysUntil < $daysUntilNext) {
                $daysUntilNext = $daysUntil;
                $nextDuty = $userDutySchedule->dutySchedule;
                $nextDuty->start_date = $userDutySchedule->start_date;
                $nextDuty->end_date = $userDutySchedule->end_date;
                $nextDuty->next_date = $today->copy()->addDays($daysUntil)->format('Y-m-d');
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $nextDuty,
        ]);
    }
    
    /**
     * Get all delegable duty schedules for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDelegableDutySchedules()
    {
        $user = Auth::user();
        $today = now();
        
        // Get all active duty schedules for the user
        $userDutySchedules = UserDutySchedule::with('dutySchedule')
            ->where('user_id', $user->id)
            ->where(function($query) use ($today) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $today->format('Y-m-d'));
            })
            ->whereHas('dutySchedule')
            ->get();
            
        if ($userDutySchedules->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No delegable duty schedules found',
                'data' => [],
            ]);
        }
        
         // Format the response
         $delegableDutySchedules = $userDutySchedules->map(function($userDutySchedule) use ($today) {
            $schedule = $userDutySchedule->dutySchedule;
            $schedule->start_date = $userDutySchedule->start_date;
            $schedule->end_date = $userDutySchedule->end_date;
            
            // Calculate the next occurrence of this duty
            $dayOfWeek = $today->dayOfWeek;
            $scheduleDayOfWeek = (int)$userDutySchedule->dutySchedule->day_of_week;
            
            // Calculate days until this duty
            $daysUntil = ($scheduleDayOfWeek - $dayOfWeek + 7) % 7;
            
            if ($daysUntil === 0) {
                // If it's today, check if the time has passed
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $schedule->start_time);
                if ($today->format('H:i') > $startTime->format('H:i')) {
                    $daysUntil = 7; // Schedule for next week
                }
            }
            
            $schedule->next_date = $today->copy()->addDays($daysUntil)->format('Y-m-d');
            
            return $schedule;
        });       
        
        return response()->json([
            'success' => true,
            'data' => $delegableDutySchedules,
        ]);
    }
}
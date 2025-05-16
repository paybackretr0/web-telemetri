<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DutySchedule;
use Illuminate\Http\Request;

class DutyController extends Controller
{
    public function index(Request $request)
    {
        $schedules = DutySchedule::with(['users' => function($query) {
            $query->wherePivot('end_date', '>=', now())
                  ->orderBy('pivot_start_date', 'asc');
        }])
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

        return view('admin.duty.index', compact('schedules'));
    }
}
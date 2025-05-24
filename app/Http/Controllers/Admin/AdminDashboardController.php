<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use App\Models\DutySchedule;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $today = Carbon::today();

        // Mengambil data statistik
        $totalUsers = User::where('role', 'pengurus')->count();
        $todayActivities = Activity::whereDate('start_time', $today)->count();
        
        // Menggunakan day_of_week dan nama hari dalam Bahasa Indonesia
        $dayName = match($today->format('l')) {
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        };
        $todayDuties = DutySchedule::where('day_of_week', $dayName)->count();
        $pendingPermissions = Permission::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'todayActivities',
            'todayDuties',
            'pendingPermissions'
        ));
    }
}
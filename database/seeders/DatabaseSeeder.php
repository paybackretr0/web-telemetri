<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AttendanceType;
use App\Models\Activity;
use App\Models\DutySchedule;
use App\Models\UserDutySchedule;
use App\Models\Program;
use App\Models\QrCode;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'cbakal987@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'divisi' => 'Pengurus Inti',
            'jabatan' => 'Administrator',
        ]);

        // Create attendance types
        $attendanceTypes = [
            ['name' => 'Kegiatan', 'description' => 'Presensi untuk kegiatan umum'],
            ['name' => 'Piket', 'description' => 'Presensi untuk jadwal piket'],
            ['name' => 'Rapat', 'description' => 'Presensi untuk rapat'],
        ];

        foreach ($attendanceTypes as $type) {
            AttendanceType::create($type);
        }

        // Create duty schedules
        $dutySchedules = [
            [
                'day_of_week' => 'Senin',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'location' => 'Lab NEO'
            ],
            [
                'day_of_week' => 'Selasa',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'location' => 'Lab NEO'
            ],
            [
                'day_of_week' => 'Rabu',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'location' => 'Lab NEO'
            ],
            [
                'day_of_week' => 'Kamis',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'location' => 'Lab NEO'
            ],
            [
                'day_of_week' => 'Jumat',
                'start_time' => '08:00',
                'end_time' => '11:00',
                'location' => 'Lab NEO'
            ],
        ];

        foreach ($dutySchedules as $schedule) {
            DutySchedule::create($schedule);
        }

        // Create sample program
        $program = Program::create([
            'title' => 'Program Kerja 2025',
            'description' => 'Program kerja NEO tahun 2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'status' => 'ongoing',
            'created_by' => $admin->id
        ]);

        // Create sample activity
        $activity = Activity::create([
            'title' => 'Rapat Program Kerja',
            'description' => 'Rapat pembahasan program kerja 2025',
            'location' => 'Lab NEO',
            'start_time' => '2025-01-10 09:00:00',
            'end_time' => '2025-01-10 12:00:00',
            'attendance_type_id' => 1,
            'created_by' => $admin->id
        ]);

        // Create QR Code for activity
        QrCode::create([
            'activity_id' => $activity->id,
            'code' => 'SAMPLE123',
            'type' => 'activity',
            'expiry_time' => '2025-01-10 12:00:00',
            'created_by' => $admin->id
        ]);

        // Create QR Code for duty
        QrCode::create([
            'code' => 'DUTY123',
            'type' => 'duty',
            'expiry_time' => '2025-01-10 17:00:00',
            'created_by' => $admin->id
        ]);
    }
}
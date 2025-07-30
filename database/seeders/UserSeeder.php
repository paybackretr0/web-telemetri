<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'cbakal987@gmail.com',
                'password' => '$2y$12$LLwyAjYc4Wu73HSg1daZyOTi70UQIGG6dx9gtPG2022RNIBk5nVg6',
                'google_id' => '103751723475868630267',
                'google_token' => null,
                'role' => 'admin',
                'jabatan' => 'Administrator',
                'divisi' => 'Pengurus Inti',
                'created_at' => '2025-05-17 09:01:26',
                'updated_at' => '2025-07-30 04:01:41',
            ],
        ]);
    }
}
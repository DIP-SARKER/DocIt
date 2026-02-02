<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'is_banned' => false,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'), // change in production
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Moderator
            [
                'name' => 'Moderator User',
                'email' => 'mod@example.com',
                'role' => 'mod',
                'is_banned' => false,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Normal verified user
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'user',
                'is_banned' => false,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Unverified user
            [
                'name' => 'Unverified User',
                'email' => 'unverified@example.com',
                'role' => 'user',
                'is_banned' => false,
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Banned user
            [
                'name' => 'Banned User',
                'email' => 'banned@example.com',
                'role' => 'user',
                'is_banned' => true,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

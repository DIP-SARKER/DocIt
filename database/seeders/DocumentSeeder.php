<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        Document::insert([
            [
                'user_id' => 1,
                'title' => 'Quarterly Financial Report Q3 2023',
                'url' => 'https://drive.google.com/file/d/example1',
                'category' => 'work',
                'description' => 'Q3 2023 financial statements and analysis',
                'is_locked' => true,
                'is_important' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 1,
                'title' => 'Academic Transcript',
                'url' => 'https://drive.google.com/file/d/example2',
                'category' => 'academic',
                'description' => 'Official university transcript',
                'is_locked' => true,
                'is_important' => false,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => 1,
                'title' => 'Passport Scan',
                'url' => 'https://drive.google.com/file/d/example3',
                'category' => 'personal',
                'description' => 'Scanned copy of passport',
                'is_locked' => true,
                'is_important' => true,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => 1,
                'title' => 'Travel Itinerary – Malaysia',
                'url' => 'https://drive.google.com/file/d/example4',
                'category' => 'travel',
                'description' => 'Flight tickets and hotel bookings',
                'is_locked' => false,
                'is_important' => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 1,
                'title' => 'Bank Statement – December',
                'url' => 'https://drive.google.com/file/d/example5',
                'category' => 'financial',
                'description' => 'Monthly bank statement',
                'is_locked' => true,
                'is_important' => false,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShortLink;

class ShortLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'title' => 'Project Documents',
                'long_url' => 'https://drive.google.com/drive/folders/1ABCxyzProjectDocs',
                'alias' => 'project-docs',
                'track_clicks' => true,
                'clicks' => 24,
            ],
            [
                'title' => 'Quarterly Financial Report',
                'long_url' => 'https://drive.google.com/file/d/FinancialReportQ3',
                'alias' => 'finance-q3',
                'track_clicks' => true,
                'clicks' => 11,
            ],
            [
                'title' => 'Academic Transcript',
                'long_url' => 'https://drive.google.com/file/d/TranscriptOfficial',
                'alias' => 'transcript',
                'track_clicks' => false,
                'clicks' => 0,
            ],
            [
                'title' => 'Resume (PDF)',
                'long_url' => 'https://drive.google.com/file/d/ResumeLatestPDF',
                'alias' => 'resume',
                'track_clicks' => true,
                'clicks' => 37,
            ],
            [
                'title' => 'Travel Itinerary – Malaysia',
                'long_url' => 'https://drive.google.com/file/d/MalaysiaTrip2024',
                'alias' => 'my-trip',
                'track_clicks' => false,
                'clicks' => 0,
            ],
        ];

        foreach ($links as $link) {
            ShortLink::create(array_merge($link, [
                'user_id' => 1,
                'expires_at' => now()->addDays(10)->toDateString(),
            ]));
        }
    }
}

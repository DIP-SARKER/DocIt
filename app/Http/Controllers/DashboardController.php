<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Document;
use App\Models\ShortLink;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $pendingTasksCount = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->count();

        $documentsCount = Document::where('user_id', $user->id)->count();

        $linksCount = ShortLink::where('user_id', $user->id)->count();

        // Recent activities (latest 5 from each table)
        $recentTasks = Task::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($task) {
                return $task->setAttribute('type', 'task');
            });

        $recentDocuments = Document::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()->map(function ($task) {
                return $task->setAttribute('type', 'document');
            });

        $recentLinks = ShortLink::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()->map(function ($task) {
                return $task->setAttribute('type', 'link');
            });

        $recentActivities = collect()
            ->merge($recentTasks)
            ->merge($recentDocuments)
            ->merge($recentLinks)
            ->sortByDesc('created_at')
            ->take(5);

        return view('profile.dashboard', compact(
            'user',
            'pendingTasksCount',
            'documentsCount',
            'linksCount',
            'recentActivities'
        ));
    }
}


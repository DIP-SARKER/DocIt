@extends('layouts.app')

@section('title', 'DocIt - Dashboard')

@section('content')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Section -->
            <div class="d-flex justify-between align-center flex-wrap gap-2 mb-4">
                <div>
                    <h1>Welcome back, {{ $user->name }}</h1>
                    <p class="text-muted">Here's what's happening with your work today.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-3 mb-4">
                <div class="card stats-card">
                    <div class="stats-icon tasks">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $pendingTasksCount }}</div>
                        <div class="stats-label">Pending Tasks</div>
                    </div>
                </div>

                <div class="card stats-card">
                    <div class="stats-icon documents">
                        <i class="far fa-folder-open"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $documentsCount }}</div>
                        <div class="stats-label">Saved Documents</div>
                    </div>
                </div>

                <div class="card stats-card">
                    <div class="stats-icon links">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $linksCount }}</div>
                        <div class="stats-label">Shortened Links</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="grid grid-2 gap-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('tasks') }}" class="btn btn-outline btn-full text-left">
                                <i class="fas fa-tasks"></i>
                                Add New Task
                            </a>
                            <a href="{{ route('documents') }}" class="btn btn-outline btn-full text-left">
                                <i class="fas fa-folder-plus"></i>
                                Add Document Link
                            </a>
                            <a href="{{ route('shortlinks') }}" class="btn btn-outline btn-full text-left">
                                <i class="fas fa-link"></i>
                                Shorten a URL
                            </a>
                            {{-- <a href="documents.html" class="btn btn-outline btn-full text-left">
                                <i class="fas fa-qrcode"></i>
                                Generate QR Code
                            </a> --}}
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header d-flex justify-between align-center">
                        <h3 class="card-title">Recent Activity</h3>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            @forelse ($recentActivities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if ($activity['type'] === 'task')
                                            <i class="fas fa-tasks"></i>
                                        @elseif ($activity['type'] === 'document')
                                            <i class="far fa-folder-open"></i>
                                        @else
                                            <i class="fas fa-link"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">
                                            @if ($activity['type'] === 'task')
                                                {{ $activity['status'] === 'completed' ? 'Completed task:' : 'Added new task:' }}
                                            @elseif ($activity['type'] === 'document')
                                                Added document:
                                            @else
                                                Shortened URL:
                                            @endif
                                            "{{ $activity['title'] }}"
                                        </div>

                                        <div class="activity-time">
                                            {{ $activity['created_at']->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex gap-2 align-center justify-center flex-column"
                                    style="margin-top: 6rem; font-size: var(--font-size-xl);">
                                    <i class="fas fa-layer-group text-muted"></i>
                                    <p class="text-muted">No recent activity found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Reminder -->
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>You're using a public computer.</strong> Remember to enable auto-logout in settings and clear
                browsing data after your session.
            </div>
        </div>
    </main>
@endsection

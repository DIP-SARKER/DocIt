@extends('layouts.app')

@section('title', 'DocIt - Tasks')

@section('content')
    <style>
        /* Columns Container */
        .columns-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-lg);
        }

        .column {
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            max-height: 60vh;
            overflow: hidden;
        }

        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: var(--space-xs);
            margin-bottom: var(--space-sm);
            border-bottom: 3px solid var(--border);
        }

        .column-title {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            font-size: var(--font-size-2xl);
            font-weight: 600;
        }

        .column-title i {
            font-size: var(--font-size-lg);
        }

        .column-title h2 {
            margin: 0px;
        }

        .column-count {
            background: var(--light);
            color: var(--dark);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .todo .column-title i {
            color: var(--warning);
        }

        .in-progress .column-title i {
            color: var(--primary);
        }

        .done .column-title i {
            color: var(--accent);
        }

        .tasks-list {
            flex: 1;
            overflow-y: auto;
            padding-top: var(--space-xs);
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
        }

        .tasks-list::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
            /* Chrome, Safari, Edge */
        }

        /* Task Card */
        .task-card {
            background: var(--card);
            border-radius: var(--radius-lg);
            padding: var(--space-md);
            margin-bottom: var(--space-md);
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-light);
            transition: var(--transition-base);
            cursor: move;
            position: relative;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-md);
        }

        .task-title {
            font-weight: 600;
            font-size: 17px;
            margin-bottom: 8px;
        }

        .task-description {
            color: var(--secondary-light);
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .task-priority {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .priority-high {
            background: #fee2e2;
            color: var(--danger);
        }

        .priority-medium {
            background: #fef3c7;
            color: #f59e0b;
        }

        .priority-low {
            background: #d1fae5;
            color: var(--secondary);
        }



        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Animation for new tasks */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .task-card {
            animation: fadeIn 0.3s ease-out;
        }

        /* Responsive Design */

        @media (max-width: 1200px) {
            .columns-container {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-md);
            }

            .column {
                max-height: 70vh;
            }

            .column-title {
                font-size: var(--font-size-xl);
            }
        }

        /* Tablets */
        @media (max-width: 900px) {
            .columns-container {
                grid-template-columns: 1fr;
                gap: var(--space-lg);
            }

            .column {
                max-height: 60vh;
            }

            .task-card {
                padding: var(--space-sm);
            }

            .task-title {
                font-size: 16px;
            }

            .task-description {
                font-size: 13px;
            }
        }

        /* Mobile devices */
        @media (max-width: 600px) {
            body {
                padding: var(--space-sm);
            }

            .column {
                max-height: none;
            }

            .column-header {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-sm);
            }

            .column-title {
                font-size: var(--font-size-lg);
                gap: var(--space-sm);
            }

            .column-count {
                width: 26px;
                height: 26px;
                font-size: var(--font-size-sm);
            }

            .task-header {
                flex-direction: column;
                gap: 8px;
            }

            .task-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .task-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .task-actions button {
                width: 36px;
                height: 36px;
            }
        }

        /* Extra small devices */
        @media (max-width: 400px) {
            .task-title {
                font-size: 15px;
            }

            .task-description {
                font-size: 12px;
            }

            .task-priority {
                font-size: 11px;
                padding: 4px 10px;
            }
        }
    </style>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-between align-center flex-wrap gap-2 mb-4">
                <div>
                    <h1>My Tasks</h1>
                    <p class="text-muted">Manage your to-do list and track progress</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="addTaskBtn">
                        <i class="fas fa-plus"></i>
                        Add New Task
                    </button>
                </div>
            </div>

            @include('profile.partials.task-form')

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="grid gap-3">
                    <div class="form-group">
                        <label for="searchDocuments" class="form-label">Search Task</label>
                        <div style="position: relative;">
                            <input type="text" id="searchTask" class="form-control"
                                placeholder="Search by title, priority, or description">
                            <i class="fas fa-search"
                                style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Columns Container -->
            <div class="columns-container">

                <!-- ================= TO DO ================= -->
                <div class="column todo" data-status="todo">
                    <div class="column-header">
                        <div class="column-title">
                            <i class="fas fa-clipboard-list"></i>
                            <h2>To Do</h2>
                        </div>
                        <div class="column-count">{{ $tasks->where('status', 'todo')->count() }}</div>
                    </div>

                    <div class="tasks-list">
                        @forelse ($tasks->where('status','todo') as $task)
                            @include('profile.partials.task-card', ['task' => $task])
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-clipboard"></i>
                                <p>No tasks yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ================= IN PROGRESS ================= -->
                <div class="column in-progress" data-status="inprogress">
                    <div class="column-header">
                        <div class="column-title">
                            <i class="fas fa-spinner"></i>
                            <h2>In Progress</h2>
                        </div>
                        <div class="column-count">{{ $tasks->where('status', 'inprogress')->count() }}</div>
                    </div>

                    <div class="tasks-list">
                        @forelse ($tasks->where('status','inprogress') as $task)
                            @include('profile.partials.task-card', ['task' => $task])
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-cogs"></i>
                                <p>No tasks in progress</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ================= DONE ================= -->
                <div class="column done" data-status="done">
                    <div class="column-header">
                        <div class="column-title">
                            <i class="fas fa-check-circle"></i>
                            <h2>Done</h2>
                        </div>
                        <div class="column-count">{{ $tasks->where('status', 'done')->count() }}</div>
                    </div>

                    <div class="tasks-list">
                        @forelse ($tasks->where('status','done') as $task)
                            @include('profile.partials.task-card', ['task' => $task])
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-flag-checkered"></i>
                                <p>No completed tasks</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>


@endsection

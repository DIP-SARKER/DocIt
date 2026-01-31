<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'user_id' => 1,
                'title' => 'Setup Laravel project',
                'description' => 'Install dependencies and configure environment',
                'priority' => 'high',
                'status' => 'done',
                'due_date' => now()->subDays(10),
            ],
            [
                'user_id' => 1,
                'title' => 'Design task database schema',
                'description' => 'Define fields, enums, and relationships',
                'priority' => 'high',
                'status' => 'done',
                'due_date' => now()->subDays(8),
            ],
            [
                'user_id' => 1,
                'title' => 'Create Task CRUD APIs',
                'description' => 'Implement store, update, delete endpoints',
                'priority' => 'high',
                'status' => 'inprogress',
                'due_date' => now()->addDays(3),
            ],
            [
                'user_id' => 1,
                'title' => 'Build task creation form UI',
                'description' => 'Connect frontend form with backend',
                'priority' => 'medium',
                'status' => 'inprogress',
                'due_date' => now()->addDays(2),
            ],
            [
                'user_id' => 1,
                'title' => 'Add task status dropdown',
                'description' => 'Support todo, inprogress, done',
                'priority' => 'medium',
                'status' => 'done',
            ],
            [
                'user_id' => 1,
                'title' => 'Implement task filters',
                'description' => 'Filter by status and priority',
                'priority' => 'medium',
                'status' => 'todo',
            ],
            [
                'user_id' => 1,
                'title' => 'Add due date validation',
                'description' => 'Ensure valid date handling',
                'priority' => 'low',
                'status' => 'done',
            ],
            [
                'user_id' => 1,
                'title' => 'UI polish for task cards',
                'description' => 'Spacing, icons, and hover effects',
                'priority' => 'low',
                'status' => 'inprogress',
            ],
            [
                'user_id' => 1,
                'title' => 'Create empty state UI',
                'description' => 'Show message when no tasks exist',
                'priority' => 'low',
                'status' => 'todo',
            ],
            [
                'user_id' => 1,
                'title' => 'Add delete confirmation modal',
                'description' => 'Prevent accidental deletions',
                'priority' => 'medium',
                'status' => 'todo',
            ],
            [
                'user_id' => 1,
                'title' => 'Implement task completion toggle',
                'description' => 'Mark task as done from UI',
                'priority' => 'high',
                'status' => 'inprogress',
                'due_date' => now()->addDays(1),
            ],
            [
                'user_id' => 1,
                'title' => 'Write feature tests for tasks',
                'description' => 'Cover CRUD operations',
                'priority' => 'high',
                'status' => 'todo',
            ],
            [
                'user_id' => 1,
                'title' => 'Optimize database queries',
                'description' => 'Use indexes and eager loading',
                'priority' => 'medium',
                'status' => 'done',
            ],
            [
                'user_id' => 1,
                'title' => 'Add API pagination',
                'description' => 'Limit tasks per page',
                'priority' => 'medium',
                'status' => 'inprogress',
            ],
            [
                'user_id' => 1,
                'title' => 'Prepare deployment checklist',
                'description' => 'Environment and production readiness',
                'priority' => 'low',
                'status' => 'todo',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}


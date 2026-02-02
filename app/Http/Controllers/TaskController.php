<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $baseQuery = Task::where('user_id', auth()->id())
            ->latest();

        // Optional: search
        if ($request->filled('q')) {
            $q = trim($request->q);
            $baseQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('priority', 'like', "%{$q}%");
            });
        }

        // 🔹 Three independent paginators
        $todoTasks = (clone $baseQuery)
            ->where('status', 'todo')
            ->paginate(5, ['*'], 'todo_page');

        $inProgressTasks = (clone $baseQuery)
            ->where('status', 'inprogress')
            ->paginate(5, ['*'], 'progress_page');

        $doneTasks = (clone $baseQuery)
            ->where('status', 'done')
            ->paginate(5, ['*'], 'done_page');

        $todoTasks->withQueryString();
        $inProgressTasks->withQueryString();
        $doneTasks->withQueryString();


        return view('profile.tasks', compact(
            'todoTasks',
            'inProgressTasks',
            'doneTasks'
        ));
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        // Create task
        Task::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ??
                now()->addWeek(),
            'status' => 'todo',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Task added successfully.');
    }

    public function nextStatus(Task $task)
    {
        $this->authorize('update', $task);

        $statusOrder = ['todo', 'inprogress', 'done'];
        $currentIndex = array_search($task->status, $statusOrder, true);

        if ($currentIndex !== false && $currentIndex < count($statusOrder) - 1) {
            $task->update(['status' => $statusOrder[$currentIndex + 1]]);
        }

        return redirect()
            ->back()
            ->with('success', 'Task status updated successfully.');
    }

    public function update(Request $request, Task $task)
    {

        $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()
            ->back()
            ->with('success', 'Task deleted successfully.');
    }
}

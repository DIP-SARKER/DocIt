<div class="task-card" data-id="{{ $task->id }}">
    <div class="task-header">
        <div>
            <div class="task-title">{{ $task->title }}</div>
            <div class="task-description">{{ $task->description }}</div>
        </div>

        <div class="task-actions">
            <button class="edit-btn" onclick="editTask({{ $task->id }})">
                <i class="fas fa-edit"></i>
            </button>
            <button class="edit-btn" style="color: var(--warning)" onclick=""
                title="Mark as {{ $task->status === 'todo' ? 'in-progress' : ($task->status === 'inprogress' ? 'Completed' : 'Todo') }}
">
                <i class="fas fa-arrow-right"></i>
            </button>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;"
                onsubmit="return confirm('Are you sure you want to delete this task?');">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-btn">
                    <i class="far fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="task-footer">
        <div class="task-priority priority-{{ $task->priority }}">
            {{ ucfirst($task->priority) }} Priority
        </div>

        @if ($task->due_date)
            <div class="document-meta">
                <i class="far fa-calendar"></i>
                {{ $task->due_date->format('d M Y') }}
            </div>
        @endif
    </div>
</div>

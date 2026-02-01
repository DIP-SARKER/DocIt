<div class="card task-card" data-id="{{ $task->id }}" data-title="{{ $task->title }}"
    data-description="{{ $task->description }}" data-priority="{{ $task->priority }}" data-status="{{ $task->status }}"
    data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
    <div class="task-header">
        <div>
            <div class="task-title">{{ $task->title }}</div>
            <div class="task-description">{{ $task->description }}</div>
        </div>

        <div class="task-actions">
            <button class="edit-btn" onclick="editTask(this)">
                <i class="fas fa-edit"></i>
            </button>
            @if ($task->status !== 'done')
                <form action="{{ route('tasks.nextStatus', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="edit-btn" style="color: var(--warning)"
                        title="Mark as {{ $task->status === 'todo' ? 'In Progress' : 'Completed' }}">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            @endif


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

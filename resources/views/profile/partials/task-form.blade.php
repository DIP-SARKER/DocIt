<!-- Add Task Form -->
<div class="card mb-4 hidden" id="addTask">
    <div class="d-flex justify-between align-center mb-3">
        <h3 class="card-title">Add New Task</h3>
    </div>
    <form method="POST" action="{{ route('tasks.store') }}" id="taskForm">
        @csrf

        <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="What needs to be done?" required>
        </div>

        <div class="form-group">
            <label class="form-label">Description (Optional)</label>
            <input type="text" name="description" class="form-control" placeholder="Brief description of the task">
        </div>

        <div class="grid grid-2 gap-4">
            <div class="form-group">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-control">
                    <option value="low" selected>Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Due Date (Optional)</label>
                <input type="date" name="due_date" class="form-control custom-date">
            </div>
        </div>

        <div class="d-flex align-center gap-2" style="justify-content: end;">
            <button type="button" class="btn btn-outline" id="closeTaskFormBtn">Cancel</button>
            <button type="submit" class="btn btn-primary" id="tasksubmitbtn">
                <i class="fas fa-save"></i>
                Save this task
            </button>
        </div>
    </form>

</div>

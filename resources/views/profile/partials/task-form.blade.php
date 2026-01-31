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

        <div class="grid grid-2 gap-3">
            <div class="form-group">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-control">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Due Date (Optional)</label>
                <input type="date" name="due_date" class="form-control">
            </div>
        </div>

        <div class="d-flex align-center gap-2" style="justify-content: end;">
            <button type="button" class="btn btn-outline" id="closeTaskFormBtn">Cancel</button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Save this task
            </button>
        </div>
    </form>

</div>
<script>
    const addTaskBtn = document.getElementById("addTaskBtn");
    const addTaskForm = document.getElementById("addTask");
    const closeTaskFormBtn = document.getElementById("closeTaskFormBtn");
    const taskForm = document.getElementById("taskForm");

    if (addTaskBtn && addTaskForm) {
        // Show add task form
        addTaskBtn.addEventListener("click", () => {
            addTaskForm.classList.remove("hidden");
            addTaskBtn.style.display = "none";

            const titleInput = document.getElementById("taskTitle");
            if (titleInput) titleInput.focus();
        });

        // Hide add task form
        if (closeTaskFormBtn) {
            closeTaskFormBtn.addEventListener("click", () => {
                addTaskForm.classList.add("hidden");
                addTaskBtn.style.display = "inline-flex";
            });
        }
    }
</script>

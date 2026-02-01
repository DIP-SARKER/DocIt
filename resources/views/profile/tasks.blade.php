@extends('layouts.app')

@section('title', 'DocIt - Tasks')

@section('content')

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

    <script>
        (() => {
            // Helpers
            const $ = (id) => document.getElementById(id);

            const addTaskBtn = $("addTaskBtn");
            const submitTaskBtn = $("tasksubmitbtn");
            const addTaskForm = $("addTask");
            const closeTaskFormBtn = $("closeTaskFormBtn");
            const taskForm = $("taskForm");

            // Guard: if core elements are missing, do nothing
            if (!addTaskBtn || !addTaskForm || !taskForm || !submitTaskBtn) return;

            const showForm = (focusId = "taskTitle") => {
                addTaskForm.classList.remove("hidden");
                addTaskBtn.style.display = "none";
                $(focusId)?.focus();
            };

            const hideForm = () => {
                addTaskForm.classList.add("hidden");
                addTaskBtn.style.display = "inline-flex";
                resetForm();
            };

            const setMethod = (method) => {
                // Ensure only one _method exists
                taskForm.querySelector('input[name="_method"]')?.remove();

                if (method && method.toUpperCase() !== "POST") {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "_method";
                    input.value = method.toUpperCase();
                    taskForm.appendChild(input);
                }
            };

            const setSubmitLabel = (mode) => {
                submitTaskBtn.innerHTML =
                    mode === "edit" ?
                    '<i class="fas fa-file-pen"></i> Update Task' :
                    '<i class="fas fa-save"></i> Save this task';
            };

            function resetForm() {
                taskForm.reset();
                taskForm.action = "/tasks";
                setMethod("POST");
                setSubmitLabel("create");
            }

            // Open/close form
            addTaskBtn.addEventListener("click", () => showForm());

            closeTaskFormBtn?.addEventListener("click", (e) => {
                e.preventDefault(); // useful if it's a <button> inside a form
                hideForm();
            });

            let originalTaskSnapshot = null;

            const getFormSnapshot = () => {
                // trim where it makes sense, keep empty as ''
                const title = (taskForm.querySelector('[name="title"]')?.value ?? "").trim();
                const description = (taskForm.querySelector('[name="description"]')?.value ?? "").trim();
                const priority = taskForm.querySelector('[name="priority"]')?.value ?? "";
                const status = taskForm.querySelector('[name="status"]')?.value ?? "";
                const due_date = taskForm.querySelector('[name="due_date"]')?.value ?? "";

                return {
                    title,
                    description,
                    priority,
                    status,
                    due_date
                };
            };

            const hasChanges = () => {
                if (!originalTaskSnapshot) return true; // not in edit mode, allow
                const now = getFormSnapshot();

                // strict compare per field
                return Object.keys(originalTaskSnapshot).some(
                    (key) => now[key] !== originalTaskSnapshot[key]
                );
            };

            // ✅ attach once (outside editTask) so it works for all submits
            taskForm.addEventListener("submit", (e) => {
                const isEditing = taskForm.querySelector('input[name="_method"]')?.value?.toUpperCase() ===
                    "PUT";

                // only block for edit updates
                if (isEditing && !hasChanges()) {
                    e.preventDefault();

                    // optional UX
                    alert("No changes detected. Update not sent.");
                    return;
                }
            });
            // Expose editTask if you use inline onclick="editTask(this)"
            window.editTask = (button) => {
                const card = button?.closest(".task-card");
                if (!card) return;

                showForm();
                setSubmitLabel("edit");

                const {
                    id,
                    title = "",
                    description = "",
                    priority = "",
                    status = "",
                    due = "",
                } = card.dataset;

                const titleEl = taskForm.querySelector('[name="title"]');
                const descEl = taskForm.querySelector('[name="description"]');
                const prioEl = taskForm.querySelector('[name="priority"]');
                const statusEl = taskForm.querySelector('[name="status"]');
                const dueEl = taskForm.querySelector('[name="due_date"]');

                if (titleEl) titleEl.value = title;
                if (descEl) descEl.value = description;
                if (prioEl) prioEl.value = priority;
                if (statusEl) statusEl.value = status;
                if (dueEl) dueEl.value = due;

                taskForm.action = `/tasks/${id}`;
                setMethod("PUT");

                // ✅ Save original snapshot AFTER values are filled
                originalTaskSnapshot = getFormSnapshot();
            };

        })();
    </script>
@endsection

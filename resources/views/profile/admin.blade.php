@extends('layouts.app')

@section('title', 'Admin Dashboard - DocIt')

@section('content')
    <main class="main-content">
        <div class="container">
            <div class="d-flex justify-between align-center mb-4 flex-wrap gap-2">
                <h1>User Management</h1>
            </div>

            <!-- Search -->
            <div class="card mb-4">
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <div class="grid gap-3">
                        <div class="form-group">
                            <label for="searchUser" class="form-label">Search User</label>
                            <div style="position: relative;">
                                <input type="text" id="searchUser" name="q" class="form-control"
                                    placeholder="Search by username or email" value="{{ request('q') }}">
                                <i class="fas fa-search"
                                    style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                                Reset
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Grid -->
            <div class="users-grid">
                @forelse ($users as $u)
                    <div class="card user-card" data-user-id="{{ $u->id }}">
                        <div class="user-header">
                            <div class="user-basic-info">
                                <div class="admin-user-name d-flex justify-between">
                                    {{ $u->name }}

                                    {{-- Status badge (adjust column name if needed) --}}
                                    @if ($u->isBanned())
                                        <span class="status-badge status-blocked" title="Blocked">
                                            <i class="fas fa-circle" style="font-size: 8px;"></i>
                                        </span>
                                    @else
                                        <span class="status-badge status-active" title="Active">
                                            <i class="fas fa-circle" style="font-size: 8px;"></i>
                                        </span>
                                    @endif
                                </div>

                                <div class="user-email">{{ $u->email }}</div>
                                <div class="user-id mt-1" style="background: transparent">ID: {{ $u->id }}</div>
                            </div>
                        </div>

                        {{-- Role (adjust if you use role column instead of is_admin) --}}
                        <form class="mb-3" method="POST" action="{{ route('admin.users.update', $u) }}"
                            onsubmit="return confirm('Save changes for {{ $u->name }}?')">
                            @csrf
                            @method('PUT')

                            {{-- Role --}}
                            <div class="user-role mb-2">
                                <select name="role" class="form-control role-select"
                                    style="
                                            color:
                                            {{ $u->role === \App\Models\User::ROLE_ADMIN
                                                ? 'var(--danger)'
                                                : ($u->role === \App\Models\User::ROLE_MOD
                                                    ? 'var(--warning)'
                                                    : 'var(--accent)') }};
                                        "
                                    onchange="
                                            if (this.value === '{{ \App\Models\User::ROLE_ADMIN }}') {
                                                this.style.color = 'var(--danger)';
                                            } else if (this.value === '{{ \App\Models\User::ROLE_MOD }}') {
                                                this.style.color = 'var(--warning)';
                                            } else {
                                                this.style.color = 'var(--accent)';
                                            }
                                        ">
                                    <option value="{{ \App\Models\User::ROLE_USER }}"
                                        {{ $u->role === \App\Models\User::ROLE_USER ? 'selected' : '' }}>
                                        User
                                    </option>

                                    <option value="{{ \App\Models\User::ROLE_MOD }}"
                                        {{ $u->role === \App\Models\User::ROLE_MOD ? 'selected' : '' }}>
                                        Moderator
                                    </option>

                                    <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                                        {{ $u->role === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                </select>


                            </div>

                            {{-- Ban toggle --}}
                            <div class="user-controls mb-3">
                                <div class="control-row">
                                    <div class="control-label">Block User Login</div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="is_banned" value="1"
                                            {{ $u->is_banned ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-center">
                                <button type="submit" class="btn btn-outline">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </form>

                        <div class="user-stats">
                            <div class="stat-item">
                                <div>
                                    <div class="stat-value">{{ $u->tasks_count ?? 0 }}</div>
                                    <div class="stat-label">Tasks</div>
                                </div>

                                <form method="POST" action="{{ route('admin.data.delete', $u) }}"
                                    onsubmit="return confirm('Delete all tasks for {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="tasks">

                                    <button type="submit" class="user-btn delete-task-btn" title="Delete all tasks">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>

                            </div>

                            <div class="stat-item">
                                <div>
                                    <div class="stat-value">{{ $u->documents_count ?? 0 }}</div>
                                    <div class="stat-label">Docs</div>
                                </div>
                                <form method="POST" action="{{ route('admin.data.delete', $u) }}"
                                    onsubmit="return confirm('Delete all documents for {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="documents">

                                    <button type="submit" class="user-btn delete-doc-btn" title="Delete all documents">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>

                            </div>

                            <div class="stat-item">
                                <div>
                                    <div class="stat-value">{{ $u->short_links_count ?? 0 }}</div>
                                    <div class="stat-label">URLs</div>
                                </div>
                                <form method="POST" action="{{ route('admin.data.delete', $u) }}"
                                    onsubmit="return confirm('Delete all short links for {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="shortlinks">

                                    <button type="submit" class="user-btn delete-url-btn" title="Delete all URLs">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card" style="padding: 1rem;">
                        <p class="text-muted" style="margin: 0;">
                            No users found{{ request('q') ? ' for "' . e(request('q')) . '"' : '' }}.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="pagination-wrapper mt-3">
                    {{ $users->links() }}
                </div>
            @endif
            <button class="btn btn-outline btn-full mt-4" id="toggleDangerZone"
                style="color: var(--danger); border-color: var(--danger);" aria-expanded="false">
                Open Danger Zone
            </button>
            <div id="dangerZone" class="card mt-4"
                style="
        border: 1px solid var(--danger);
        padding: var(--space-lg);
        display: none;
        flex-direction: column;
        gap: var(--space-lg);
    ">

                {{-- Export --}}
                <div class="d-flex justify-between align-center flex-wrap gap-2">
                    <div>
                        <strong>Export Data</strong>
                        <p class="text-muted" style="font-size: var(--font-size-sm); margin: 0;">
                            Download a full backup of application data.
                        </p>
                    </div>

                    <a class="btn btn-outline" href="{{ route('admin.tools.export') }}">
                        <i class="fas fa-download"></i>
                        Export All Data
                    </a>
                </div>

                <hr style="border-color: var(--border);">

                {{-- Clear Cache --}}
                <div class="d-flex justify-between align-center flex-wrap gap-2">
                    <div>
                        <strong>Clear Cache</strong>
                        <p class="text-muted" style="font-size: var(--font-size-sm); margin: 0;">
                            Clear application cache and config.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.tools.clearCache') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Clear application cache now?')">
                            <i class="fas fa-broom"></i>
                            Clear Cache
                        </button>
                    </form>
                </div>

                <hr style="border-color: var(--danger);">

                {{-- Danger Zone --}}
                <div
                    style="
            background: rgba(239, 68, 68, 0.06);
            border: 1px dashed var(--danger);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
        ">

                    <strong style="color: var(--danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                        Danger Zone
                    </strong>

                    <p class="text-muted" style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        This action will permanently delete all data. This cannot be undone.
                    </p>

                    <form method="POST" action="{{ route('admin.tools.purge') }}"
                        onsubmit="return confirm('This will permanently delete all data of DocIt. Continue?')"
                        style="margin-top: var(--space-md);">
                        @csrf
                        @method('DELETE')

                        <div class="form-group" style="max-width: 320px;">
                            <input type="text" name="confirm" class="form-control" placeholder="Type DELETE_ALL"
                                required>

                            <p class="text-muted" id="purgeHint"
                                style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                                <i class="fas fa-info-circle"></i>
                                Type <strong>DELETE_ALL</strong> to enable deletion.
                            </p>
                        </div>

                        <button type="submit" class="btn btn-danger mt-2">
                            <i class="far fa-trash-alt"></i>
                            Delete All Data
                        </button>
                    </form>
                </div>
            </div>


        </div>
    </main>
    <script>
        (function() {
            const toggleBtn = document.getElementById("toggleDangerZone");
            const dangerZone = document.getElementById("dangerZone");

            if (!toggleBtn || !dangerZone) return;

            toggleBtn.addEventListener("click", function() {
                const isOpen = dangerZone.style.display !== 'none';

                if (isOpen) {
                    // Close
                    dangerZone.style.display = "none";
                    toggleBtn.textContent = "Open Danger Zone";
                    toggleBtn.setAttribute("aria-expanded", "false");
                } else {
                    // Open
                    dangerZone.style.display = "flex";
                    toggleBtn.textContent = "Close Danger Zone";
                    toggleBtn.setAttribute("aria-expanded", "true");

                    // Optional: scroll into view
                    dangerZone.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        })();
    </script>
@endsection

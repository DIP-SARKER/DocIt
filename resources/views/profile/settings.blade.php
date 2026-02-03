@extends('layouts.app')

@section('title', 'DocIt - Settings')

@section('content')
    <main class="main-content">
        <div class="container">
            <div class="mb-4">
                <h1>Settings</h1>
                <p class="text-muted">Manage your account and security preferences</p>
            </div>

            {{-- Profile Section --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                </div>

                <div class="card-body">
                    @php
                        $u = auth()->user();
                        $initials = collect(explode(' ', $u->name))
                            ->filter()
                            ->take(2)
                            ->map(fn($p) => strtoupper(substr($p, 0, 1)))
                            ->join('');
                    @endphp

                    <div class="d-flex align-center gap-3 mb-4">
                        <div class="avatar" style="width: 64px; height: 64px; font-size: var(--font-size-xl);">
                            {{ $initials ?: 'U' }}
                        </div>
                        <div>
                            <h4 style="margin-bottom: 2px;">{{ $u->name }}</h4>
                            <p class="text-muted" style="margin-bottom: 0;">{{ $u->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('settings.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-2 gap-3">
                            <div class="form-group">
                                <label for="profileName" class="form-label">Full Name</label>
                                <input type="text" id="profileName" name="name" class="form-control"
                                    value="{{ old('name', $u->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="profileEmail" class="form-label">Email Address</label>
                                <input type="email" id="profileEmail" name="email" class="form-control"
                                    value="{{ old('email', $u->email) }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                            <div class="d-flex gap-2">
                                {{-- <a href="{{ route('password.edit') }}" class="btn btn-outline" id="changePasswordBtn">
                                    <i class="fas fa-key"></i>
                                    Change Password
                                </a> --}}
                                {{-- <button class="btn btn-outline" id="sessionManagerBtn">
                                    <i class="fas fa-desktop"></i>
                                    Manage Sessions
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Preferences Section (UI only for now) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Preferences</h3>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-2">Theme</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">
                            Choose your preferred interface theme
                        </p>

                        <div class="d-flex gap-3 flex-wrap">
                            <div class="card"
                                style="padding: var(--space-md); border: 2px solid var(--primary); cursor: default;">
                                <div class="d-flex align-center gap-2">
                                    <div
                                        style="width: 20px; height: 20px; border-radius: 50%; background-color: var(--primary);">
                                    </div>
                                    <span>Dark</span>
                                </div>
                                <div class="mt-2"
                                    style="width: 220px; max-width: 100%; height: 60px; border-radius: var(--radius-sm);
                                    background: linear-gradient(135deg, #1E293B 0%, #0F172A 50%, #334155 100%);">
                                </div>
                            </div>

                            <div class="card"
                                style="padding: var(--space-md); border: 1px solid var(--border); opacity: 0.5;">
                                <div class="d-flex align-center gap-2">
                                    <div
                                        style="width: 20px; height: 20px; border-radius: 50%; background-color: var(--secondary);">
                                    </div>
                                    <span>Light</span>
                                    <span class="badge">Coming Soon</span>
                                </div>
                                <div class="mt-2"
                                    style="width: 220px; max-width: 100%; height: 60px; border-radius: var(--radius-sm);
                                    background: linear-gradient(135deg, #F8FAFC 0%, #FFFFFF 50%, #E2E8F0 100%);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card border-danger" style="border-color: var(--danger);">
                <div class="card-header" style="border-color: var(--danger);">
                    <h3 class="card-title text-danger">Danger Zone</h3>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-2">Export Your Data</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">
                            Download a copy of all your tasks, documents, and links
                        </p>

                        <a class="btn btn-outline" href="{{ route('settings.export') }}">
                            <i class="fas fa-download"></i>
                            Export All Data
                        </a>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-2">Delete Account</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">
                            Permanently delete your account and all data. This action cannot be undone.
                        </p>

                        <form method="POST" action="{{ route('settings.account.destroy') }}"
                            onsubmit="return confirm('This will permanently delete your account. Continue?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">
                                <i class="far fa-trash-alt"></i>
                                Delete Account
                            </button>
                        </form>
                    </div>

                    <div>
                        <h4 class="mb-2">Logout</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">
                            Sign out from all devices
                        </p>

                        <form method="POST" action="{{ route('settings.logout.everywhere') }}"
                            onsubmit="return confirm('Logout from all devices?')">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout Everywhere
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

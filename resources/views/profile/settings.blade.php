@extends('layouts.app')

@section('title', 'DocIt - Settings')

@section('content')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="mb-4">
                <h1>Settings</h1>
                <p class="text-muted">Manage your account and security preferences</p>
            </div>

            <!-- Profile Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                </div>

                <div class="card-body">
                    <div class="d-flex align-center gap-3 mb-4">
                        <div class="avatar" style="width: 64px; height: 64px; font-size: var(--font-size-xl);">JD</div>
                        <div>
                            <h4>John Doe</h4>
                            <p class="text-muted">john.doe@example.com</p>
                            <button class="btn btn-sm btn-outline">Change Avatar</button>
                        </div>
                    </div>

                    <form id="profileForm">
                        <div class="grid grid-2 gap-3">
                            <div class="form-group">
                                <label for="profileName" class="form-label">Full Name</label>
                                <input type="text" id="profileName" class="form-control" value="John Doe" required>
                            </div>

                            <div class="form-group">
                                <label for="profileEmail" class="form-label">Email Address</label>
                                <input type="email" id="profileEmail" class="form-control" value="john.doe@example.com"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="profileBio" class="form-label">Bio (Optional)</label>
                            <textarea id="profileBio" class="form-control" rows="3" placeholder="Tell us a little about yourself">Product manager who loves organizing work efficiently.</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Security Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Security Settings</h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle"></i>
                        <strong>Public Computer Mode:</strong> Enable these settings when using shared or public
                        devices.
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-between align-center mb-3">
                            <div>
                                <h4>Auto Logout</h4>
                                <p class="text-muted" style="font-size: var(--font-size-sm);">Automatically log out
                                    after a period of inactivity</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="autoLogoutToggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div id="autoLogoutSettings" class="pl-4">
                            <div class="form-group">
                                <label for="logoutTime" class="form-label">Logout after inactivity of:</label>
                                <select id="logoutTime" class="form-control" style="max-width: 200px;">
                                    <option value="5">5 minutes</option>
                                    <option value="15" selected>15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="60">1 hour</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-between align-center mb-2">
                            <div>
                                <h4>Clear Data on Logout</h4>
                                <p class="text-muted" style="font-size: var(--font-size-sm);">Automatically clear
                                    browsing data when logging out from public computers</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="clearDataToggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-between align-center mb-2">
                            <div>
                                <h4>Two-Factor Authentication</h4>
                                <p class="text-muted" style="font-size: var(--font-size-sm);">Add an extra layer of
                                    security to your account</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="twoFactorToggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" id="changePasswordBtn">
                            <i class="fas fa-key"></i>
                            Change Password
                        </button>
                        <button class="btn btn-outline" id="sessionManagerBtn">
                            <i class="fas fa-desktop"></i>
                            Manage Sessions
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preferences Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Preferences</h3>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-2">Theme</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">Choose your preferred
                            interface theme</p>

                        <div class="d-flex gap-3">
                            <div class="card"
                                style="padding: var(--space-md); border: 2px solid var(--primary); cursor: pointer;">
                                <div class="d-flex align-center gap-2">
                                    <div
                                        style="width: 20px; height: 20px; border-radius: 50%; background-color: var(--primary);">
                                    </div>
                                    <span>Light</span>
                                </div>
                                <div class="mt-2"
                                    style="width: 100%; height: 60px; border-radius: var(--radius-sm); background: linear-gradient(135deg, #F8FAFC 0%, #FFFFFF 50%, #E2E8F0 100%);">
                                </div>
                            </div>

                            <div class="card"
                                style="padding: var(--space-md); border: 1px solid var(--border); cursor: pointer; opacity: 0.5;">
                                <div class="d-flex align-center gap-2">
                                    <div
                                        style="width: 20px; height: 20px; border-radius: 50%; background-color: var(--secondary);">
                                    </div>
                                    <span>Dark</span>
                                    <span class="badge">Coming Soon</span>
                                </div>
                                <div class="mt-2"
                                    style="width: 100%; height: 60px; border-radius: var(--radius-sm); background: linear-gradient(135deg, #1E293B 0%, #0F172A 50%, #334155 100%);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-2">Default Page</h4>
                        <p class="text-muted mb-2" style="font-size: var(--font-size-sm);">Choose which page opens
                            when you log in</p>

                        <select id="defaultPage" class="form-control" style="max-width: 300px;">
                            <option value="dashboard">Dashboard</option>
                            <option value="tasks">Tasks</option>
                            <option value="documents">Documents</option>
                            <option value="shortener">URL Shortener</option>
                        </select>
                    </div>

                    <div>
                        <h4 class="mb-2">Notifications</h4>
                        <p class="text-muted mb-2" style="font-size: var(--font-size-sm);">Choose how you want to be
                            notified</p>

                        <div class="form-check">
                            <input type="checkbox" id="notifyTasks" class="form-check-input" checked>
                            <label for="notifyTasks" class="form-check-label">Task reminders</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="notifySecurity" class="form-check-input" checked>
                            <label for="notifySecurity" class="form-check-label">Security alerts</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="notifyUpdates" class="form-check-input">
                            <label for="notifyUpdates" class="form-check-label">Product updates</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger" style="border-color: var(--danger);">
                <div class="card-header" style="border-color: var(--danger);">
                    <h3 class="card-title text-danger">Danger Zone</h3>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-2">Export Your Data</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">Download a copy of all your
                            tasks, documents, and links</p>
                        <button class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Export All Data
                        </button>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-2">Delete Account</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">Permanently delete your
                            account and all data. This action cannot be undone.</p>
                        <button class="btn btn-danger" id="deleteAccountBtn">
                            <i class="far fa-trash-alt-alt"></i>
                            Delete Account
                        </button>
                    </div>

                    <div>
                        <h4 class="mb-2">Logout</h4>
                        <p class="text-muted mb-3" style="font-size: var(--font-size-sm);">Sign out from all devices
                        </p>
                        <button class="btn btn-primary" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout Everywhere
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

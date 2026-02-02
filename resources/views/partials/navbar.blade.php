<!-- Header -->
<header class="navbar">
    <div class="container navbar-container">
        <a href="{{ route('home') }}" class="navbar-brand not-hover">
            <i class="fas fa-file-alt"></i>
            DocIt
        </a>
        @php
            $status = Auth::check() ? 'Authenticated' : 'Guest';
            $user = Auth::user();
        @endphp

        <!-- User Profile with Dropdown -->
        <div class="navbar-user">
            <div class="nav-user">
                <div class="avatar">{{ strtoupper(substr($user->name ?? 'Guest', 0, 2)) }}</div>
                <span class="user-name">{{ $user->name ?? 'Guest' }}</span>
                <i class="fas fa-chevron-down"></i>
            </div>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu">
                @if ($status == 'Guest')
                    <div class="guest-options">
                        <a href="{{ route('login') }}" class="dropdown-item"
                            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                            <i class="fas fa-sign-in-alt dropdown-icon"></i>
                            <span>Login</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('register') }}" class="dropdown-item"
                            style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                            <i class="fas fa-rocket dropdown-icon"></i>
                            <span>Get Started</span>
                        </a>
                    </div>
                @endif
                @if ($status == 'Authenticated')
                    <div class="user-options">
                        @if ($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item"
                                style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                                <i class="fas fa-user-tie dropdown-icon"></i>
                                <span>Admin Panel</span>
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}" class="dropdown-item"
                            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                            <i class="fas fa-tachometer-alt dropdown-icon"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('tasks') }}" class="dropdown-item">
                            <i class="fas fa-list-check dropdown-icon"></i>
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('documents') }}" class="dropdown-item">
                            <i class="far fa-folder-open dropdown-icon"></i>
                            <span>Documents</span>
                        </a>
                        <a href="{{ route('shortlinks') }}" class="dropdown-item">
                            <i class="fas fa-link dropdown-icon"></i>
                            <span>Url Shortener</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i class="fas fa-cog dropdown-icon"></i>
                            <span>Settings</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href='#' class="dropdown-item"
                            style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt dropdown-icon"></i>
                            <span>Logout</span>
                        </a>

                    </div>
                @endif
            </div>
        </div>

    </div>
</header>

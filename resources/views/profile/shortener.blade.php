@extends('layouts.app')

@section('title', 'DocIt - URL Shortener')

@section('content')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-between align-center flex-wrap gap-2 mb-4">
                <div>
                    <h1>URL Shortener</h1>
                    <p class="text-muted">Create short, memorable links for quick access and sharing</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="addUrlBtn">
                        <i class="fas fa-plus"></i>
                        Shorten a new URL
                    </button>
                </div>
            </div>

            @include('profile.partials.url-form')
            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="grid gap-3">
                    <div class="form-group">
                        <label for="searchLink" class="form-label">Search Link</label>
                        <div style="position: relative;">
                            <input type="text" id="searchLink" class="form-control" placeholder="Search by title">
                            <i class="fas fa-search"
                                style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Short URLs -->
            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h3 class="card-title">Active Shortened URLs</h3>
                    <span class="badge" id="linkCount">{{ $links->count() }} Links</span>

                </div>

                <div class="card-body">

                    <!-- URL List -->
                    @if ($links->count())
                        <div id="urlList">
                            @foreach ($links as $link)
                                <div class="short-url-item" data-id="{{ $link->id }}">

                                    <div class="short-url-info">
                                        <div class="title-action gap-2 d-flex align-center">
                                            <h4 class="link-title">{{ $link->title }}</h4>

                                            @if ($link->expires_at)
                                                <span class="badge expires-badge"
                                                    style="color: var(--warning); background: #f8cb5836;">
                                                    Expires On: <span class="expires-text">{{ $link->expires_at }}</span>
                                                </span>
                                            @else
                                                <span class="badge expires-badge hidden"
                                                    style="color: var(--warning); background: #f8cb5836;">
                                                    Expires On: <span class="expires-text"></span>
                                                </span>
                                            @endif

                                            <span
                                                class="badge badge-accent clicks-badge {{ $link->track_clicks ? '' : 'hidden' }}">
                                                <span class="clicks-text">{{ $link->clicks }} </span>&nbsp;clicks
                                            </span>
                                        </div>

                                        <div class="short-url-result">
                                            <span class="short-url-text">{{ url($link->alias) }}</span>
                                        </div>

                                        <div class="short-url-original">
                                            <span class="long-url-text">{{ $link->long_url }}</span>
                                        </div>
                                    </div>

                                    <div class="task-actions">
                                        <button class="edit-btn i-btn copy-link" data-url="{{ url($link->alias) }}"
                                            title="Copy short link">
                                            <i class="far fa-copy"></i>
                                        </button>

                                        <button class="edit-btn i-btn" onclick="" title="Expand 10 days expiry"
                                            style="color: var(--accent)"> <i class="far fa-square-plus"></i> </button>

                                        <button class="i-btn js-edit" style="color: var(--warning)"
                                            data-id="{{ $link->id }}" title="Edit link">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="POST" action="{{ route('shortlinks.destroy', $link->id) }}"
                                            onsubmit="return confirm('Delete this short link?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn i-btn" title="Delete link">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div id="emptyUrlState" class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-link" style="font-size: 3rem; color: var(--text-muted);"></i>
                            </div>
                            <h4>No shortened URLs yet</h4>
                            <p class="text-muted">Create your first short URL above to get started</p>
                        </div>
                    @endif

                </div>


                <div class="card-footer d-flex justify-between">
                    <div class="text-muted">
                        Total clicks: <strong id="totalClicks">{{ $links->sum('clicks') }}</strong>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            /* ===============================
               UI TOGGLE (FORM SHOW / HIDE)
            =============================== */

            const addUrlBtn = document.getElementById("addUrlBtn");
            const addUrlForm = document.getElementById("addUrl");
            const cancelUrlFormBtn = document.getElementById("cancelUrlFormBtn");

            if (addUrlBtn && addUrlForm) {
                addUrlBtn.addEventListener("click", () => {
                    addUrlForm.classList.remove("hidden");
                    addUrlBtn.style.display = "none";

                    const titleInput = document.getElementById("linkTitle");
                    if (titleInput) titleInput.focus();
                });

                if (cancelUrlFormBtn) {
                    cancelUrlFormBtn.addEventListener("click", () => {
                        addUrlForm.classList.add("hidden");
                        addUrlBtn.style.display = "inline-flex";
                    });
                }
            }

            /* ===============================
               ELEMENT REFERENCES
            =============================== */

            const form = document.getElementById("shortenerForm");
            const linkCountEl = document.getElementById("linkCount");
            const totalClicksEl = document.getElementById("totalClicks");
            const emptyState = document.getElementById("emptyUrlState");
        });
    </script>



@endsection

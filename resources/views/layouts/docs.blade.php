@extends('layouts.app')

@section('title', 'DocIt - Documents')

@section('content')
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

            <h1>Showing {{ $name }}'s documents.</h1>

            <!-- Security Notice -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-shield-alt"></i>
                <strong>Security First:</strong>
                DocIt stores only document links, not the files themselves.
                Access your documents at <a>
                    {{ config('app.public_url') }}/doc/yourusername </a>
            </div>



            <!-- Search & Filter (JS only) -->
            <form method="GET" action="{{ route('documents.public', $name) }}" class="card mb-4">
                <div class="grid grid-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Search Documents</label>
                        <input type="text" id="searchDocuments" name="q" class="form-control"
                            placeholder="Search by title, category, or description" value="{{ request('q') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Filter by Category</label>
                        <select id="filterCategory" name="category" class="form-control">
                            <option value="all" {{ request('category', 'all') === 'all' ? 'selected' : '' }}>All
                                Categories</option>
                            <option value="personal" {{ request('category') === 'personal' ? 'selected' : '' }}>Personal
                            </option>
                            <option value="academic" {{ request('category') === 'academic' ? 'selected' : '' }}>Academic
                            </option>
                            <option value="work" {{ request('category') === 'work' ? 'selected' : '' }}>Work</option>
                            <option value="financial" {{ request('category') === 'financial' ? 'selected' : '' }}>Financial
                            </option>
                            <option value="travel" {{ request('category') === 'travel' ? 'selected' : '' }}>Travel</option>
                            <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-1" style="justify-content: end;">
                    <a class="btn btn-outline" href="{{ route('documents.public', $name) }}">
                        Reset
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                </div>
            </form>

            <!-- Document Grid -->
            @if ($documents->count())
                <div class="grid grid-3" id="documentGrid">

                    @foreach ($documents as $document)
                        <div class="card document-card">

                            <div class="title-action"
                                style="
                                justify-content: space-between;">
                                <h4>{{ $document->title }}</h4>

                                @if ($document->is_locked)
                                    <div class="document-lock locked">
                                        <i class="fas fa-lock"></i> Locked
                                    </div>
                                @endif
                            </div>

                            @if ($document->description)
                                <p class="text-muted document-description">{{ $document->description }}</p>
                            @endif

                            <div class="d-flex gap-2 mb-1">
                                @if ($document->category)
                                    <span class="badge badge-primary">{{ ucfirst($document->category) }}</span>
                                @endif
                                @if ($document->is_important)
                                    <span class="badge badge-danger">Important</span>
                                @endif
                            </div>

                            <div class="document-icons mb-1" style="flex-shrink: 0;">
                                <span class="text-muted">
                                    <i class="far fa-calendar"></i>
                                    {{ $document->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <div class="document-actions">
                                <button class="btn btn-sm btn-outline btn-full copy-link" data-url="{{ $document->url }}">
                                    <i class="far fa-copy"></i> Copy Link
                                </button>

                                <a href="{{ $document->url }}" target="_blank" class="btn btn-sm btn-primary btn-full">
                                    <i class="fas fa-external-link-alt"></i> Open
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($documents->hasPages())
                    <div class="pagination-wrapper mt-3">
                        {{ $documents->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="card text-center py-4">
                    <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <h4>No documents found</h4>
                    <p class="text-muted">Add your first document link</p>
                </div>
            @endif

        </div>
    </main>

    <script>
        (() => {
            // ========== Copy Link ==========
            document.addEventListener("click", async (e) => {
                const btn = e.target.closest(".copy-link");
                if (!btn) return;

                const url = btn.dataset.url;
                if (!url) return;

                try {
                    await navigator.clipboard.writeText(url);

                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    btn.disabled = true;

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                    }, 1500);
                } catch (err) {
                    const tempInput = document.createElement("input");
                    tempInput.value = url;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempInput);

                    alert("Link copied to clipboard");
                }
            });
            // Auto-submit filters (optional)
            const search = document.getElementById("searchDocuments");
            const category = document.getElementById("filterCategory");

            // Submit when category changes
            category?.addEventListener("change", () => {
                category.closest("form")?.submit();
            });

            // Submit when pressing Enter in search input
            search?.addEventListener("keydown", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    search.closest("form")?.submit();
                }
            });
        })();
    </script>



@endsection

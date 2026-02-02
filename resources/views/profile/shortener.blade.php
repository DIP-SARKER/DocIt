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
                            <input type="text" id="searchLink" class="form-control" placeholder="Search by title"
                                value="{{ request('q') }}">
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
                    <span class="badge" id="linkCount">{{ $links->total() }} Links</span>

                </div>

                <div class="card-body">

                    <!-- URL List -->
                    @if ($links->count())
                        <div id="urlList">
                            @foreach ($links as $link)
                                <div class="short-url-item" data-id="{{ $link->id }}" data-title="{{ $link->title }}"
                                    data-long_url="{{ $link->long_url }}" data-alias="{{ $link->alias }}"
                                    data-track_clicks="{{ $link->track_clicks ? 1 : 0 }}">

                                    <div class="short-url-info">
                                        <div class="title-action gap-2 d-flex align-center"
                                            style="flex-wrap: wrap; row-gap: var(--space-xs);">
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
                                            @if ($link->track_clicks)
                                                <span
                                                    class="badge badge-accent clicks-badge {{ $link->track_clicks ? '' : 'hidden' }}">
                                                    <span class="clicks-text">{{ $link->clicks }} </span>&nbsp;clicks
                                                </span>
                                            @endif

                                        </div>

                                        <div class="short-url-result">
                                            <span class="short-url-text">{{ url($link->alias) }}</span>
                                        </div>

                                        <div class="short-url-original">
                                            <span class="long-url-text">{{ $link->long_url }}</span>
                                        </div>
                                    </div>

                                    <div class="task-actions url-actions">
                                        <button class="edit-btn i-btn copy-link" data-url="{{ url($link->alias) }}"
                                            title="Copy short link">
                                            <i class="far fa-copy"></i>
                                        </button>

                                        <button class="i-btn js-edit" style="color: var(--warning)"
                                            data-id="{{ $link->id }}" title="Edit link">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if (now()->diffInDays($link->expires_at) < 6)
                                            <form action="{{ route('shortlinks.expand', $link) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="edit-btn i-btn" title="Expand 10 days expiry"
                                                    style="color: var(--accent)"> <i class="far fa-square-plus"></i>
                                                </button>
                                            </form>
                                        @endif
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
                        Total clicks: <strong id="totalClicks">{{ $totalClicks }}</strong>

                    </div>
                    @if ($links->hasPages())
                        <div>
                            {{ $links->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
    <script>
        (() => {
            //userfriendly form
            document.addEventListener("DOMContentLoaded", () => {
                const urlInput = document.getElementById("longUrl");
                const lhint = document.getElementById("urlHint");

                if (!urlInput || !lhint) return;
                urlInput.addEventListener("input", function() {
                    const value = this.value.trim();

                    // Reset to default
                    lhint.innerHTML =
                        `<i class="fas fa-info-circle"></i> This URL must be unique and properly formatted.`;
                    lhint.style.color = "var(--text-muted)";

                    // If user typed something but URL is invalid
                    if (value.length > 0 && !this.checkValidity()) {
                        lhint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Please enter a valid URL (include http:// or https://).`;
                        lhint.style.color = "var(--danger)";
                        return;
                    }

                    // Length warning (soft)
                    if (value.length > 1900 && value.length <= 2048) {
                        lhint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Approaching maximum URL length.`;
                        lhint.style.color = "var(--warning)";
                    }

                    // Hard limit hint
                    if (value.length > 2048) {
                        lhint.innerHTML =
                            `<i class="fas fa-info-circle"></i> URL exceeds the maximum allowed length (2048).`;
                        lhint.style.color = "var(--danger)";
                    }
                });




                const aliasInput = document.getElementById("customAlias");
                const hint = document.getElementById("aliasHint");

                if (!aliasInput || !hint) return;

                aliasInput.addEventListener("input", function() {
                    // Restrict characters
                    this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, "");

                    // Enforce max length
                    if (this.value.length > 50) {
                        this.value = this.value.slice(0, 50);
                    }

                    // Gentle minimum length hint (only if user typed something)
                    if (this.value.length > 0 && this.value.length < 5) {
                        hint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Minimum 5 characters required.`;
                        hint.style.color = "var(--danger)";
                    } else {
                        hint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Custom name using letters, numbers, dashes, or underscores only.`;
                        hint.style.color = "var(--text-muted)";
                    }
                });

            });
            // ========== Searching ==========
            document.getElementById('searchLink')?.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter') return;
                const q = e.target.value.trim();
                const url = new URL(window.location.href);
                if (q) url.searchParams.set('q', q);
                else url.searchParams.delete('q');
                url.searchParams.delete('page'); // reset pagination when searching
                window.location.href = url.toString();
            });
            // ========== Helpers ==========
            const $ = (id) => document.getElementById(id);

            const setMethod = (form, method) => {
                form.querySelector('input[name="_method"]')?.remove();
                if (method && method.toUpperCase() !== "POST") {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "_method";
                    input.value = method.toUpperCase();
                    form.appendChild(input);
                }
            };

            const copyText = async (text) => {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (err) {
                    // fallback
                    const temp = document.createElement("input");
                    temp.value = text;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand("copy");
                    document.body.removeChild(temp);
                    return true;
                }
            };

            // ========== Copy short link (list buttons) ==========
            document.addEventListener("click", async (e) => {
                const btn = e.target.closest(".copy-link");
                if (!btn) return;

                const url = btn.dataset.url;
                if (!url) return;

                const ok = await copyText(url);
                if (!ok) return;

                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }, 1200);
            });

            // ========== Form + UI refs ==========
            const addUrlBtn = $("addUrlBtn");
            const addUrlFormWrap = $("addUrl");
            const cancelUrlFormBtn = $("cancelUrlFormBtn");
            const form = $("shortenerForm");

            if (!addUrlBtn || !addUrlFormWrap || !cancelUrlFormBtn || !form) return;

            const cardTitleEl = addUrlFormWrap.querySelector(".card-title");
            const submitBtn = form.querySelector('button[type="submit"]');

            const titleEl = $("linkTitle"); // name="title"
            const longUrlEl = $("longUrl"); // name="long_url"
            const aliasEl = $("customAlias"); // name="alias"
            const trackClicksEl = $("trackClicks"); // name="track_clicks"

            // ========== Change detection (Edit mode only) ==========
            let originalSnapshot = null;

            const getSnapshot = () => ({
                title: (titleEl?.value ?? "").trim(),
                long_url: (longUrlEl?.value ?? "").trim(),
                alias: (aliasEl?.value ?? "").trim(),
                track_clicks: trackClicksEl?.checked ? "1" : "0",
            });

            const hasChanges = () => {
                if (!originalSnapshot) return true;
                const now = getSnapshot();
                return Object.keys(originalSnapshot).some((k) => now[k] !== originalSnapshot[k]);
            };

            // ========== UI State ==========
            const showForm = () => {
                addUrlFormWrap.classList.remove("hidden");
                addUrlBtn.style.display = "none";
                titleEl?.focus();
            };

            const setCreateMode = () => {
                form.reset();
                form.action = "{{ route('shortlinks.store') }}";
                setMethod(form, "POST");
                originalSnapshot = null;

                if (cardTitleEl) cardTitleEl.textContent = "Shorten a URL";
                if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-link"></i> Shorten URL';
            };

            const setEditMode = (id) => {
                form.action = `/shortlinks/${id}`;
                setMethod(form, "PUT");

                if (cardTitleEl) cardTitleEl.textContent = "Edit Short Link";
                if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-file-pen"></i> Update Link';
            };

            const hideForm = () => {
                addUrlFormWrap.classList.add("hidden");
                addUrlBtn.style.display = "inline-flex";
                setCreateMode();
            };

            // ========== Toggle create form ==========
            addUrlBtn.addEventListener("click", () => {
                setCreateMode();
                showForm();
            });

            cancelUrlFormBtn.addEventListener("click", (e) => {
                e.preventDefault();
                hideForm();
            });

            // ========== Edit button ==========
            document.addEventListener("click", (e) => {
                const editBtn = e.target.closest(".js-edit");
                if (!editBtn) return;

                const row = editBtn.closest(".short-url-item");
                if (!row) return;

                const d = row.dataset;

                showForm();

                if (titleEl) titleEl.value = d.title ?? "";
                if (longUrlEl) longUrlEl.value = d.long_url ?? "";
                if (aliasEl) aliasEl.value = d.alias ?? "";
                if (trackClicksEl) trackClicksEl.checked = (d.track_clicks === "1");

                setEditMode(d.id);

                // snapshot AFTER filling
                originalSnapshot = getSnapshot();
            });

            // ========== Block update when no changes ==========
            form.addEventListener("submit", (e) => {
                const method = form.querySelector('input[name="_method"]')?.value?.toUpperCase();
                const isEditing = method === "PUT";

                if (isEditing && !hasChanges()) {
                    e.preventDefault();
                    alert("No changes detected. Nothing to update.");
                }
            });

        })();
    </script>


@endsection

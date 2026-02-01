@extends('layouts.app')

@section('title', 'DocIt - Document Links')

@section('content')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

            <!-- Page Header -->
            <div class="d-flex justify-between align-center flex-wrap gap-2 mb-4">
                <div>
                    <h1>Document Links</h1>
                    <p class="text-muted">Store and access important document links from anywhere</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="addDocBtn">
                        <i class="fas fa-plus"></i>
                        Add Link
                    </button>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-shield-alt"></i>
                <strong>Security First:</strong>
                DocIt stores only document links, not the files themselves.
                Access your documents at <a href="{{ config('app.public_url') }}/doc/{{ Auth::user()->name }}"
                    target="_blank" rel="noopener noreferrer">
                    {{ config('app.public_url') }}/doc/{{ Auth::user()->name }} </a>
            </div>


            @include('profile.partials.document-form')

            <!-- Search & Filter (JS only) -->
            <div class="card mb-4">
                <div class="grid grid-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Search Documents</label>
                        <input type="text" id="searchDocuments" class="form-control"
                            placeholder="Search by title, category, or description">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Filter by Category</label>
                        <select id="filterCategory" class="form-control">
                            <option value="all">All Categories</option>
                            <option value="personal">Personal</option>
                            <option value="academic">Academic</option>
                            <option value="work">Work</option>
                            <option value="financial">Financial</option>
                            <option value="travel">Travel</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Document Grid -->
            @if ($documents->count())
                <div class="grid grid-3" id="documentGrid">

                    @foreach ($documents as $document)
                        <div class="card document-card" data-id="{{ $document->id }}" data-title="{{ $document->title }}"
                            data-url="{{ $document->url }}" data-category="{{ $document->category ?? '' }}"
                            data-description="{{ $document->description ?? '' }}"
                            data-locked="{{ $document->is_locked ? 1 : 0 }}"
                            data-important="{{ $document->is_important ? 1 : 0 }}">

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

                                <div type="button" class="task-actions" style="margin-top: auto">
                                    <button class="edit-btn doc-edit-btn"
                                        title="
                                    Edit this">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('documents.destroy', $document->id) }}"
                                        onsubmit="return confirm('Delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
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

            // ========== Document Form (Create/Edit) ==========
            const addDocBtn = document.getElementById("addDocBtn");
            const addDocForm = document.getElementById("addDocForm");
            const cancelDocFormBtn = document.getElementById("cancelDocFormBtn");
            const DocForm = document.getElementById("DocForm");

            // If this page doesn't have the form, skip only this section
            if (!addDocBtn || !addDocForm || !cancelDocFormBtn || !DocForm) return;

            const titleEl = DocForm.querySelector('[name="title"]');
            const urlEl = DocForm.querySelector('[name="url"]');
            const categoryEl = DocForm.querySelector('[name="category"]');
            const descriptionEl = DocForm.querySelector('[name="description"]');
            const lockedEl = DocForm.querySelector('[name="is_locked"]');
            const importantEl = DocForm.querySelector('[name="is_important"]');
            const submitBtn = DocForm.querySelector('button[type="submit"]');
            const cardTitleEl = addDocForm.querySelector(".card-title");

            let originalDocSnapshot = null;

            const getDocSnapshot = () => ({
                title: (titleEl?.value ?? "").trim(),
                url: (urlEl?.value ?? "").trim(),
                category: categoryEl?.value ?? "",
                description: (descriptionEl?.value ?? "").trim(),
                is_locked: lockedEl?.checked ? "1" : "0",
                is_important: importantEl?.checked ? "1" : "0",
            });

            const hasDocChanges = () => {
                if (!originalDocSnapshot) return true;
                const now = getDocSnapshot();
                return Object.keys(originalDocSnapshot).some((k) => now[k] !== originalDocSnapshot[k]);
            };

            const setMethod = (method) => {
                DocForm.querySelector('input[name="_method"]')?.remove();
                if (method && method.toUpperCase() !== "POST") {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "_method";
                    input.value = method.toUpperCase();
                    DocForm.appendChild(input);
                }
            };

            const setSubmitLabel = (mode) => {
                if (!submitBtn) return;
                submitBtn.innerHTML =
                    mode === "edit" ?
                    '<i class="fas fa-file-pen"></i> Update Document Link' :
                    '<i class="fas fa-save"></i> Save Document Link';
            };

            const setCardTitle = (mode) => {
                if (!cardTitleEl) return;
                cardTitleEl.textContent = mode === "edit" ? "Edit Document Link" : "Add New Document Link";
            };

            const showForm = () => {
                addDocForm.classList.remove("hidden");
                addDocBtn.style.display = "none";
                titleEl?.focus();
            };

            const resetForm = () => {
                DocForm.reset();
                DocForm.action = "{{ route('documents.store') }}";
                setMethod("POST");
                setSubmitLabel("create");
                setCardTitle("create");
                originalDocSnapshot = null;
            };

            const hideForm = () => {
                addDocForm.classList.add("hidden");
                addDocBtn.style.display = "inline-flex";
                resetForm();
            };

            const fillFormFromCard = (card) => {
                const d = card.dataset;
                if (titleEl) titleEl.value = d.title ?? "";
                if (urlEl) urlEl.value = d.url ?? "";
                if (categoryEl) categoryEl.value = d.category ?? "";
                if (descriptionEl) descriptionEl.value = d.description ?? "";
                if (lockedEl) lockedEl.checked = d.locked === "1";
                if (importantEl) importantEl.checked = d.important === "1";
            };

            const setEditMode = (id) => {
                DocForm.action = `/documents/${id}`;
                setMethod("PUT");
                setSubmitLabel("edit");
                setCardTitle("edit");
            };

            addDocBtn.addEventListener("click", () => {
                resetForm();
                showForm();
            });

            cancelDocFormBtn.addEventListener("click", (e) => {
                e.preventDefault();
                hideForm();
            });

            document.addEventListener("click", (e) => {
                const editBtn = e.target.closest(".doc-edit-btn");
                if (!editBtn) return;

                const card = editBtn.closest(".document-card");
                if (!card) return;

                showForm();
                fillFormFromCard(card);
                setEditMode(card.dataset.id);
                originalDocSnapshot = getDocSnapshot();
            });

            DocForm.addEventListener("submit", (e) => {
                const method = DocForm.querySelector('input[name="_method"]')?.value?.toUpperCase();
                const isEditing = method === "PUT";

                if (isEditing && !hasDocChanges()) {
                    e.preventDefault();
                    alert("No changes detected. Nothing to update.");
                }
            });
        })();
    </script>



@endsection

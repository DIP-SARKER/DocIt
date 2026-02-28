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


            {{-- @include('profile.partials.document-form') --}}
            <livewire:documents-manager />

        </div>
    </main>

    <script>
        (() => {
            document.addEventListener("DOMContentLoaded", () => {
                const urlInput = document.getElementById("longUrl");
                const hint = document.getElementById("urlHint");

                if (!urlInput || !hint) return;

                urlInput.addEventListener("input", function() {
                    const value = this.value.trim();

                    // Reset to default
                    hint.innerHTML =
                        `<i class="fas fa-info-circle"></i> This URL must be unique and properly formatted.`;
                    hint.style.color = "var(--text-muted)";

                    // If user typed something but URL is invalid
                    if (value.length > 0 && !this.checkValidity()) {
                        hint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Please enter a valid URL (include http:// or https://).`;
                        hint.style.color = "var(--danger)";
                        return;
                    }

                    // Length warning (soft)
                    if (value.length > 1900 && value.length <= 2048) {
                        hint.innerHTML =
                            `<i class="fas fa-info-circle"></i> Approaching maximum URL length.`;
                        hint.style.color = "var(--warning)";
                    }

                    // Hard limit hint
                    if (value.length > 2048) {
                        hint.innerHTML =
                            `<i class="fas fa-info-circle"></i> URL exceeds the maximum allowed length (2048).`;
                        hint.style.color = "var(--danger)";
                    }
                });
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

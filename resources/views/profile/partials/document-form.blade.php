<!-- Add Document Form -->
<div class="card mb-4 hidden" id="addDocForm">
    <div class="d-flex justify-between align-center mb-3">
        <h3 class="card-title">Add New Document Link</h3>
    </div>
    <form method="POST" action="{{ route('documents.store') }}" id="DocForm">
        @csrf
        <div class="form-group">
            <label class="form-label">Document Title *</label>
            <input type="text" name="title" id="docTitle" class="form-control"
                placeholder="e.g., Quarterly Report Q3 2023" required>
        </div>

        <div class="form-group">
            <label class="form-label">Document URL *</label>
            <input type="url" name="url" class="form-control" placeholder="https://drive.google.com/file/..."
                required>
        </div>

        <div class="grid grid-2 gap-3">
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">Select category</option>
                    <option value="personal">Personal</option>
                    <option value="academic">Academic</option>
                    <option value="work">Work</option>
                    <option value="financial">Financial</option>
                    <option value="travel">Travel</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Description (Optional)</label>
                <input type="text" name="description" class="form-control"
                    placeholder="Brief description of the document">
            </div>
        </div>

        <div class="d-flex justify-between align-center mt-3">
            <div class="form-check">
                <input type="checkbox" name="is_locked" value="1" class="form-check-input">
                <label class="form-check-label">Lock with password</label>
            </div>

            <div class="form-check">
                <input type="checkbox" name="is_important" value="1" class="form-check-input">
                <label class="form-check-label">Mark as important</label>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline" id="cancelDocFormBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Document Link
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    const addDocBtn = document.getElementById("addDocBtn");
    const addDocForm = document.getElementById("addDocForm");
    const cancelDocFormBtn = document.getElementById("cancelDocFormBtn");
    const DocForm = document.getElementById("DocForm");

    if (addDocBtn && addDocForm) {
        // Show URL shortener form
        addDocBtn.addEventListener("click", () => {
            addDocForm.classList.remove("hidden");
            addDocBtn.style.display = "none";

            const titleInput = document.getElementById("docTitle");
            if (titleInput) titleInput.focus();
        });

        // Hide URL shortener form
        if (cancelDocFormBtn) {
            cancelDocFormBtn.addEventListener("click", () => {
                addDocForm.classList.add("hidden");
                addDocBtn.style.display = "inline-flex";
            });
        }
    }
</script>

<?php

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $category = 'all';

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public array $form = [
        'title' => '',
        'url' => '',
        'category' => '',
        'description' => '',
        'is_locked' => false,
        'is_important' => false,
    ];

    public function getDocumentsProperty()
    {
        return Document::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->category !== 'all', fn($q) => $q->where('category', $this->category))
            ->latest()
            ->paginate(6);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $id = $this->editingId;

        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.url' => ['required', 'url', 'max:2048', Rule::unique('documents', 'url')->ignore($id)],
            'form.category' => ['nullable', 'string', 'max:100'],
            'form.description' => ['nullable', 'string'],
            'form.is_locked' => ['boolean'],
            'form.is_important' => ['boolean'],
        ];
    }

    public function create()
    {
        $this->editingId = null;
        $this->resetValidation();

        $this->form = [
            'title' => '',
            'url' => '',
            'category' => '',
            'description' => '',
            'is_locked' => false,
            'is_important' => false,
        ];

        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $doc = Document::where('user_id', Auth::id())->findOrFail($id);

        $this->editingId = $doc->id;
        $this->resetValidation();

        $this->form = [
            'title' => $doc->title ?? '',
            'url' => $doc->url ?? '',
            'category' => $doc->category ?? '',
            'description' => $doc->description ?? '',
            'is_locked' => (bool) $doc->is_locked,
            'is_important' => (bool) $doc->is_important,
        ];

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $doc = Document::where('user_id', Auth::id())->findOrFail($this->editingId);

            // optional: if you use policies, uncomment:
            // $this->authorize('update', $doc);

            $doc->update([
                'title' => $this->form['title'],
                'url' => $this->form['url'],
                'category' => $this->form['category'] ?: null,
                'description' => $this->form['description'] ?: null,
                'is_locked' => (bool) $this->form['is_locked'],
                'is_important' => (bool) $this->form['is_important'],
            ]);

            session()->flash('success', 'Document updated successfully.');
        } else {
            // optional: if you use policies, uncomment:
            // $this->authorize('create', Document::class);

            Document::create([
                'user_id' => Auth::id(),
                'title' => $this->form['title'],
                'url' => $this->form['url'],
                'category' => $this->form['category'] ?: null,
                'description' => $this->form['description'] ?: null,
                'is_locked' => (bool) $this->form['is_locked'],
                'is_important' => (bool) $this->form['is_important'],
            ]);

            session()->flash('success', 'Document link added successfully.');
        }

        $this->showForm = false;
        $this->editingId = null;

        // Reset form after save
        $this->form = [
            'title' => '',
            'url' => '',
            'category' => '',
            'description' => '',
            'is_locked' => false,
            'is_important' => false,
        ];

        $this->resetValidation();
        $this->resetPage(); // so new item appears on page 1
    }

    public function delete($id)
    {
        $doc = Document::where('user_id', Auth::id())->findOrFail($id);

        // optional: if you use policies, uncomment:
        // $this->authorize('delete', $doc);

        $doc->delete();

        session()->flash('success', 'Document deleted successfully.');

        // If last item on page deleted, go back a page safely
        if ($this->documents->count() === 1 && $this->page > 1) {
            $this->previousPage();
        }
    }
};
?>

<div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success mb-3">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header action (Add Link) --}}
    <div class="d-flex justify-between align-center mb-3">
        <div></div>
        <button class="btn btn-primary" wire:click="create">
            <i class="fas fa-plus"></i>
            Add Link
        </button>
    </div>

    {{-- Create/Edit Form --}}
    @if ($showForm)
        <div class="card mb-4" id="addDocForm">
            <div class="d-flex justify-between align-center mb-3">
                <h3 class="card-title">
                    {{ $editingId ? 'Edit Document Link' : 'Add New Document Link' }}
                </h3>
            </div>

            <form wire:submit.prevent="save">
                <div class="form-group">
                    <label class="form-label">Document Title *</label>
                    <input type="text" wire:model.defer="form.title" class="form-control"
                        placeholder="e.g., Quarterly Report Q3 2023">
                    @error('form.title')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Document URL *</label>
                    <input type="url" wire:model.defer="form.url" class="form-control"
                        placeholder="https://drive.google.com/file/...">
                    <p class="text-muted" style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        This URL must be unique and properly formatted. [max:2048]
                    </p>
                    @error('form.url')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select wire:model.defer="form.category" class="form-control">
                            <option value="">Select category</option>
                            <option value="personal">Personal</option>
                            <option value="academic">Academic</option>
                            <option value="work">Work</option>
                            <option value="financial">Financial</option>
                            <option value="travel">Travel</option>
                            <option value="other">Other</option>
                        </select>
                        @error('form.category')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" wire:model.defer="form.description" class="form-control"
                            placeholder="Brief description of the document">
                        @error('form.description')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-between align-center mt-3 form-actions">
                    <div class="form-check">
                        <input type="checkbox" wire:model.defer="form.is_locked" class="form-check-input">
                        <label class="form-check-label">Lock with password</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" wire:model.defer="form.is_important" class="form-check-input">
                        <label class="form-check-label">Mark as important</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline" wire:click="closeForm">Cancel</button>

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                @if ($editingId)
                                    <i class="fas fa-file-pen"></i> Update Document Link
                                @else
                                    <i class="fas fa-save"></i> Save Document Link
                                @endif
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Search -->
    <div class="card mb-4">
        <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
            placeholder="Search documents...">

        <select wire:model.live="category" class="form-control mt-2">
            <option value="all">All</option>
            <option value="personal">Personal</option>
            <option value="academic">Academic</option>
            <option value="work">Work</option>
            <option value="financial">Financial</option>
            <option value="travel">Travel</option>
            <option value="other">Other</option>
        </select>
    </div>

    @if ($this->documents->count())
        <div class="grid grid-3" id="documentGrid">
            @foreach ($this->documents as $document)
                <div class="card document-card">

                    <div class="title-action" style="justify-content: space-between;">
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

                    <div class="document-icons mb-1">
                        <span class="text-muted doc-date">
                            <i class="far fa-calendar"></i>
                            {{ $document->created_at->format('M d, Y') }}
                        </span>

                        <div class="task-actions" style="margin-top: auto">
                            <button class="edit-btn" wire:click="edit({{ $document->id }})" title="Edit this">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button wire:click="delete({{ $document->id }})"
                                onclick="confirm('Delete this document?') || event.stopImmediatePropagation()"
                                class="delete-btn" title="Delete">
                                <i class="far fa-trash-alt"></i>
                            </button>
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

        @if ($this->documents->hasPages())
            <div class="pagination-wrapper mt-3">
                {{ $this->documents->links() }}
            </div>
        @endif
    @else
        <div class="card text-center py-4">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--text-muted);"></i>
            <h4>No documents found</h4>
            <p class="text-muted">Add your first document link</p>
        </div>
    @endif

</div>

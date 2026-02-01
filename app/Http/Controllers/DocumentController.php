<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $documents = Document::whereBelongsTo(auth()->user())
            ->latest()
            ->get();
        return view('profile.documents', compact('documents'));
    }
    public function show(string $name)
    {
        $user = User::where('name', $name)->first();

        // 2️⃣ If user not found → 404 or custom page
        if (!$user) {
            return redirect()->route('invalid');
        }

        // 3️⃣ Get only unlocked documents for that user
        $documents = Document::where('user_id', $user->id)
            ->where('is_locked', false)
            ->latest()
            ->get();

        return view('layouts.docs', compact('documents'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048|unique:documents,url',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:150',
            'is_locked' => 'nullable|boolean',
            'is_important' => 'nullable|boolean',
        ]);

        // Create document
        Document::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_locked' => $request->has('is_locked'),
            'is_important' => $request->has('is_important'),
        ]);
        return redirect()->back()->with('success', 'Document link added successfully.');
    }

    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048|unique:documents,url,' . $document->id,
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:150',
            'is_locked' => 'nullable|boolean',
            'is_important' => 'nullable|boolean',
        ]);

        $document->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_locked' => $request->has('is_locked'),
            'is_important' => $request->has('is_important'),
        ]);

        return redirect()->back()->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $document->delete();
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
